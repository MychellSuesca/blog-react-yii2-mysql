import React, { useState } from "react";
import PropTypes from "prop-types";
import { connect } from "react-redux";
import { Link, Redirect } from "react-router-dom";
import AuthService from "../services";
import classNames from "classnames";
import { useForm } from "react-hook-form";

const Login = (props) => {
    const { register, handleSubmit, errors } = useForm();
    const [stateForm, setStateForm] = useState({ email: "", password: "" });
    const [loading, setLoading] = useState(false);
    const [response, setResponse] = useState({ error: false, message: "" });

    // If user is already authenticated we redirect to entry location.
    const { from } = props.location.state || { from: { pathname: "/" } };
    const { isAuthenticated } = props;

    const handleChange = (e) => {
        const { name, value } = e.target;
        setStateForm({
            ...stateForm,
            [name]: value,
        });
    };

    const handleBlur = (e) => {
        const { value } = e.target;
        // Avoid validation until input has a value.
        if (value === "") {
            return;
        }
    };

    const onSubmit = () => {
        //e.preventDefault();
        const { email, password } = stateForm;
        let encriptPwd = password;
        const credentials = {
            email,
            encriptPwd,
        };
        setLoading(true);
        submit(credentials);
    };

    const submit = (credentials) => {
        props.dispatch(AuthService.login(credentials)).catch((err) => {
            console.log(err);
            const errorsCredentials = Object.values(err.errors);
            errorsCredentials.join(" ");
            const responses = {
                error: true,
                message: errorsCredentials[0],
            };
            setResponse(responses);
            setLoading(false);
        });
    };

    return (
        <>
            {isAuthenticated && <Redirect to={from} />}
            <div className="d-flex flex-column flex-md-row align-items-md-center py-5">
                <div className="container">
                    <div className="row">

                        <div className="section-about col-lg-12 mb-4 mb-lg-0">
                            <h2>Aplicación de Blog</h2>
                            <ul>
                                <li>Este proyecto se realiza con React.js como front-end</li>
                                <li>PHP con framework Yii2 como back-end</li>
                                <li>MySQL como gestor de base de datos</li>
                            </ul>
                            <p>
                                <a href="https://github.com/MychellSuesca/blog-react-yii2-mysql">
                                    Para ver la documentación en GitHub ingresa aquí.
                                </a>
                            </p>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <h4 className="row justify-content-md-center">Iniciar Sesión</h4>
                        
                        <div className="row justify-content-md-center text-center">
    
                            <div className="card-login card mb-6 mb-lg-0">
                                <div className="card-body">
                                    <form
                                        className="form-horizontal"
                                        method="POST"
                                        onSubmit={handleSubmit(onSubmit)}
                                        autoComplete="off"
                                    >
                                        {response.error && (
                                            <div
                                                className="alert alert-danger"
                                                role="alert"
                                            >
                                                El email y/o contraseña son incorrectos
                                            </div>
                                        )}
    
                                        <div className="form-group">
                                            <input
                                                id="email"
                                                type="email"
                                                name="email"
                                                maxLength={100}
                                                className={classNames(
                                                    "form-control",
                                                    {
                                                        "is-invalid":
                                                            "email" in errors,
                                                    }
                                                )}
                                                placeholder="Email"
                                                required
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                disabled={loading}
                                                ref={register({
                                                    required: true,
                                                })}
                                            />
                                        </div>
    
                                        <div className="form-group">
                                            <input
                                                id="password"
                                                type="password"
                                                maxLength={20}
                                                minLength={6}
                                                className={classNames(
                                                    "form-control",
                                                    {
                                                        "is-invalid":
                                                            "password" in
                                                            errors,
                                                    }
                                                )}
                                                name="password"
                                                placeholder="Contraseña"
                                                required
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                disabled={loading}
                                                ref={register({
                                                    required: true,
                                                })}
                                            />
                                        </div>
    
                                        <div className="form-group text-center">
                                            <button
                                                type="submit"
                                                className={classNames(
                                                    "btn btn-primary",
                                                    {
                                                        "btn-loading": loading,
                                                    }
                                                )}
                                            >
                                                Ingresar
                                            </button>
                                        </div>
    
                                        <div className="login-invite-text text-center">
                                            {"¿Ya tienes una cuenta?"}{" "}
                                            <Link to="/register">Registrarme</Link>
                                            .
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

Login.propTypes = {
    dispatch: PropTypes.func.isRequired,
    isAuthenticated: PropTypes.bool.isRequired,
};

const mapStateToProps = (state) => ({
    isAuthenticated: state.Auth.isAuthenticated,
});
export default connect(mapStateToProps)(Login);
