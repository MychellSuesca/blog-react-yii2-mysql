import Http from '../Http';
import * as action from '../store/actions';

export function login(credentials) {
  return (dispatch) => new Promise((resolve, reject) => {
    Http.post('http://localhost/blog-react-yii2-mysql/blog-back/web/auth/login', credentials)
      .then((res) => {
        dispatch(action.authLogin(res.data));
        return resolve();
      })
      .catch((err) => {
        if(err.response){
          const errors = err.response.data.message;
          const status = err.response.status;
          const data = {
            status,
            errors,
          };
          return reject(data);
        }
      });
  });
}

export function register(credentials) {
  return (dispatch) => new Promise((resolve, reject) => {
    Http.post('http://localhost/blog-react-yii2-mysql/blog-back/web/crud/user/save', credentials)
      .then((res) => resolve(res.data))
      .catch((err) => {
        if(err.response){
          const errors = err.response.data.message;
          const status = err.response.status;
          const data = {
            status,
            errors,
          };
          return reject(data);
        }
      });
  });
}

export function resetPassword(credentials) {
  return (dispatch) => new Promise((resolve, reject) => {
    Http.post('/api/v1/auth/forgot-password', credentials)
      .then((res) => resolve(res.data))
      .catch((err) => {
        const { status, errors } = err.response.data;
        const data = {
          status,
          errors,
        };
        return reject(data);
      });
  });
}

export function updatePassword(credentials) {
  return (dispatch) => new Promise((resolve, reject) => {
    Http.post('/api/v1/auth/password-reset', credentials)
      .then((res) => {
        const { status } = res.data.status;
        if (status === 202) {
          const data = {
            error: res.data.message,
            status,
          };
          return reject(data);
        }
        return resolve(res);
      })
      .catch((err) => {
        const { status, errors } = err.response.data;
        const data = {
          status,
          errors,
        };
        return reject(data);
      });
  });
}
