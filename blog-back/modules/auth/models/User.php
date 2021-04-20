<?php

namespace app\modules\auth\models;

use Yii;
use yii\base\Security;
use yii\web\IdentityInterface;

class User extends \app\modules\auth\models\base\UserBase implements IdentityInterface
{

    public $auth_key;
    public $accessToken;
    protected $adminName = 'Administrador';

    /**
     * This function after find records to format rows
     */
    public function afterFind()
    {
        $this->tipo_usuario = (($this->tipo_usuario == 1) ? ["value" => "1", "label" => "Administrador"]: ["value" => "0", "label" => "Usuario"]) ;
        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['celular', 'nombre', 'email', 'password', 'fecha_creacion', 'fecha_actualizacion'], 'required'],
        ];
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(intval($id));
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = Yii::$app->jwt->getParser()->parse((string) $token); // Parses from a string
        $token->getClaims(); // Retrieves the token claims

        $data = Yii::$app->jwt->getValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setId('4f1g23a12aa');

        if ($token->validate($data)) {
            $user = new User;
            $user->id = base64_decode($token->getClaim('uid'));
            $user->nombre = $token->getClaim('nombre');
            $user->email = $token->getClaim('email');
            $user->celular = $token->getClaim('celular');
            return new static($user);
        }
    }

    /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByemail($email)
    {
        return static::findOne(['login' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generate jwt token
     */
    public function generateJwtToken()
    {
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        return (string) $jwt->getBuilder()
        //->issuedBy('http://example.com')// Configures the issuer (iss claim)
        //->permittedFor('http://example.org')// Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->expiresAt($time + (12 * 3600)) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', base64_encode($this->id))
            ->withClaim('sub', $this->id)
            ->withClaim('nombre', utf8_encode($this->nombre))
            ->withClaim('celular', utf8_encode($this->celular))
            ->withClaim('email', utf8_encode($this->email))
            ->getToken($signer, $key); // Retrieves the generated token
    }

}
