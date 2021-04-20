import React, { useState } from "react";
import PropTypes from "prop-types";
import { connect } from "react-redux";
import { Link, Redirect } from "react-router-dom";
import classNames from "classnames";
import AuthService from "../services";
import { useForm } from "react-hook-form";

const Register = (props) => {
    const { register, handleSubmit, errors } = useForm();
    const [stateForm, setStateForm] = useState({
        nombre: "",
        celular: "",
        tipo_usuario: 0,
        email: "",
        password: "",
    });
    const [loading, setLoading] = useState(false);
    const [success, setSuccess] = useState(false);
    const [response, setResponse] = useState({ error: false, message: "" });

    // If user is already authenticated we redirect to entry location.
    const { isAuthenticated } = props;

    const onHandleTelephoneChange = (e) => {
        const { name, value } = e.target;
        let celular = value;
        if (!Number(celular) && value !== "") {
            return;
        }
        setStateForm({
            ...stateForm,
            [name]: value,
        });
    };

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
        const {
            email,
            celular,
            tipo_usuario,
            password,
            nombre,
        } = stateForm;
        const credentials = {
            nombre,
            email,
            celular,
            tipo_usuario,
            password,
        };
        setLoading(true);
        submit(credentials);
    };

    const submit = (credentials) => {
        props
            .dispatch(AuthService.register(credentials))
            .then(setSuccess(true))
            .catch((err) => {
                console.log(err);
                const errorsCredentials = Object.values(err.errors);
                errorsCredentials.join(" ");
                const responses = {
                    error: true,
                    message: errorsCredentials,
                };
                setResponse(responses);
                setLoading(false);
                setSuccess(false);
            });
    };
    return (
        <>
            {isAuthenticated && <Redirect to="/" replace />}
            <div className="d-flex flex-column flex-row align-content-center py-5">
                <div className="container">
                    <div className="row">
                        <div className="section-login col-lg-6 ml-auto mr-auto">
                            <h4>Registrarme</h4>

                            <div className="card-login card mb-3">
                                <div className="card-body">
                                    {response.error && (
                                        <div
                                            className="alert alert-danger text-center"
                                            role="alert"
                                        >
                                            {response.message}
                                        </div>
                                    )}

                                    {success && (
                                        <div
                                            className="alert alert-success text-center"
                                            role="alert"
                                        >
                                            Se ha registrado exitosamente.
                                            <br />
                                            <Link to="/" href="/">
                                                Inicie sesión de su nueva cuenta su correo electrónico y contraseña registrada.
                                            </Link>
                                        </div>
                                    )}

                                    {!success && (
                                        <form
                                            className="form-horizontal"
                                            method="POST"
                                            onSubmit={handleSubmit(onSubmit)}
                                            autocomplete="off"
                                        >
                                            <div className="form-group">
                                                <input
                                                    id="nombre"
                                                    type="nombre"
                                                    maxLength={100}
                                                    name="nombre"
                                                    value={stateForm.nombre}
                                                    className={classNames(
                                                        "form-control",
                                                        {
                                                            "is-invalid":
                                                                "name" in
                                                                errors,
                                                        }
                                                    )}
                                                    placeholder="Nombre"
                                                    required
                                                    onChange={handleChange}
                                                    onBlur={handleBlur}
                                                    disabled={loading}
                                                    ref={register({
                                                        required: true,
                                                    })}
                                                />
                                                {errors.nombre && (
                                                    <span className="invalid-feedback">
                                                        El campo es requerido
                                                    </span>
                                                )}
                                            </div>

                                            <div className="form-group">
                                                <input
                                                    id="email"
                                                    type="email"
                                                    name="email"
                                                    maxLength={50}
                                                    value={stateForm.email}
                                                    className={classNames(
                                                        "form-control",
                                                        {
                                                            "is-invalid":
                                                                "email" in
                                                                errors,
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
                                                {errors.email && (
                                                    <span className="invalid-feedback">
                                                        El campo es requerido
                                                    </span>
                                                )}
                                            </div>

                                            <div className="form-group">
                                                <input
                                                    id="password"
                                                    type="password"
                                                    maxLength={15}
                                                    minLength={6}
                                                    value={stateForm.password}
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
                                                {errors.password && (
                                                    <span className="invalid-feedback">
                                                        El campo es requerido
                                                    </span>
                                                )}
                                            </div>

                                            <div className="form-group">
                                                <input
                                                    id="celular"
                                                    type="text"
                                                    name="celular"
                                                    value={stateForm.celular}
                                                    maxLength={10}
                                                    minLength={7}
                                                    className={classNames(
                                                        "form-control",
                                                        {
                                                            "is-invalid":
                                                                "celular" in
                                                                errors,
                                                        }
                                                    )}
                                                    placeholder="Número Movil"
                                                    required
                                                    onChange={
                                                        onHandleTelephoneChange
                                                    }
                                                    onBlur={handleBlur}
                                                    disabled={loading}
                                                    ref={register({
                                                        required: true,
                                                    })}
                                                />
                                                {errors.celular && (
                                                    <span className="invalid-feedback">
                                                        This field is required
                                                    </span>
                                                )}
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
                                                    Registrarme
                                                </button>
                                            </div>
                                        </form>
                                    )}
                                </div>
                            </div>

                            {!success && (
                                <div className="password-reset-link text-center">
                                    <Link to="/" href="/">
                                        ¿Ya estas registrado? Ingresar
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

Register.propTypes = {
    dispatch: PropTypes.func.isRequired,
    isAuthenticated: PropTypes.bool.isRequired,
};

const mapStateToProps = (state) => ({
    isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(Register);
