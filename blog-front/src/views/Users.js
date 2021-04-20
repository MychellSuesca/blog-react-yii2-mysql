import React, { useState, useEffect } from "react";
import Http from "../Http";
import { useForm } from "react-hook-form";
import Dropdown from "../components/DropdownTipoU";
import swal from "sweetalert";

const api = "http://localhost/blog-react-yii2-mysql/blog-back/web/crud/user";

let options = [
    { value: "0", label: "Usuario" },
    { value: "1", label: "Administrador" },
];

const getData = (hook) => {
    Http.get(api)
    .then((response) => {
        const data  = response.data.items;
        if(data.length > 0 ){
            hook(data);
        }
    })
    .catch((err) => {
        console.log(err);
    });
}

const Users = () => {
    const session = JSON.parse(window.localStorage.getItem("user"));
    const { id } = session;
    const { register, handleSubmit } = useForm();
    const [dataState, setData] = useState([]);
    const [categoriasState, setCategoria] = useState([]);
    const [error, setError] = useState(false);
    const [stateForm, setStateForm] = useState({
        nombre: "",
        email: "",
        password: "",
        celular: "",
        fecha_creacion: "",
        fecha_actualizacion: "",
        tipo_usuario: {},
    });

    useEffect(() => {
        getData(setData);
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setStateForm({
            ...stateForm,
            [name]: value,
        });
    };

    const onSubmit = () => {
        addUser(stateForm);
    };

    const addUser = (usuario) => {
        if (usuario?.id) {
            Http.post(api+"/save/"+ usuario.id, usuario)
                .then((response) => {
                    getData(setData);
                    setStateForm({
                        nombre: "",
                        email: "",
                        password: "",
                        celular: "",
                        fecha_creacion: "",
                        fecha_actualizacion: "",
                        tipo_usuario: {},
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar el usuario");
                });
        } else {
            Http.post(api+"/save", usuario)
                .then(({ data }) => {
                    getData(setData);
                    setStateForm({
                        nombre: "",
                        email: "",
                        password: "",
                        celular: "",
                        fecha_creacion: "",
                        fecha_actualizacion: "",
                        tipo_usuario: {},
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar el usuario");
                });
        }
    };

    const editUser = (usuario) => {
        const { id } = usuario;
        let form = dataState.filter((us) => us.id === id);
        setStateForm(form[0]);
    };

    const deleteUsuario = (e) => {
        const { key } = e.target.dataset;
        swal({
            title: "Estas seguro?",
            text: "Una vez eliminado, no puede volver a ver este usuario!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                Http.delete(`${api}/delete/${key}`)
                .then((response) => {
                    getData(setData);
                    swal("El usuario ha sido eliminado!", {
                        icon: "success",
                    });
                })
                .catch((errorResponse) => {
                    setError("Hubo un error de procesamiento");
                    swal(
                        "No se pudo eliminar!",
                        "Hubo un error en procesamiento.",
                        { icon: "warning" }
                    );
                });
            }
        });
    };
    return (
        <div className="container">
            <div className="row">
                    <h1 className="text-center mb-4">
                        Agregar/Actualizar un Usuario
                    </h1>

                    <form method="post" onSubmit={handleSubmit(onSubmit)}>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <input
                                id="nombre"
                                type="nombre"
                                name="nombre"
                                className="form-control"
                                placeholder="Nombre"
                                required
                                onChange={handleChange}
                                value={stateForm.nombre}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                        <div className="form-group col-md-6">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                className="form-control"
                                placeholder="Email"
                                required
                                onChange={handleChange}
                                value={stateForm.email}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                className="form-control"
                                placeholder="Contraseña"
                                required
                                onChange={handleChange}
                                value={stateForm.password}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                         <div className="form-group col-md-6">
                            <input
                                id="celular"
                                type="celular"
                                name="celular"
                                className="form-control"
                                placeholder="Número móvil"
                                required
                                onChange={handleChange}
                                value={stateForm.celular}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <div className="form-group col-md-6">
                            <Dropdown
                                name="tipo_usuario"
                                title={
                                    stateForm?.tipo_usuario?.label ??
                                    "Tipo Usuario"
                                }
                                options={options}
                                setStateForm={setStateForm}
                                stateForm={stateForm}
                            />
                            </div>
                        </div>
                    </div>
                    <div className="p-1 mb-1">
                        <div className="text-center">
                            <button type="submit" className="btn btn-block btn-outline-primary">
                                Guardar
                            </button>
                        </div>
                    </div>
                    </form>
                    {error && (
                        <div className="alert alert-warning" role="alert">
                            {error}
                        </div>
                    )}
            </div>
            
            <div className="row">
                <div className="col">
                    <h1 className="text-center">Lista de Usuarios</h1>
                    <table className="table">
                        <tbody>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Contraseña</th>
                                <th>Número Movil</th>
                                <th>Tipo de Usuario</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
                                <th colSpan="2">Acciones</th>
                            </tr>
                            {dataState.map((usuario) => {
                                return(
                                    <tr key={usuario.id}>
                                        <td>{usuario.nombre}</td>
                                        <td>{usuario.email}</td>
                                        <td>******</td>
                                        <td>{usuario.celular}</td>
                                        <td>{usuario.tipo_usuario?.label}</td>
                                        <td>{usuario.fecha_creacion}</td>
                                        <td>{usuario.fecha_actualizacion}</td>
                                        <td>
                                            <span
                                                type="button"
                                                className="badge badge-danger"
                                                onClick={
                                                    usuario.user_id === id
                                                        ? deleteUsuario
                                                        : () =>
                                                              console.log(
                                                                  "Not an owner"
                                                              )
                                                }
                                                data-key={usuario.id}
                                            >
                                                Eliminar
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                type="button"
                                                className="badge badge-dark"
                                                onClick={
                                                    usuario.user_id === id
                                                        ? () =>
                                                              editUser(
                                                                  usuario
                                                              )
                                                        : () =>
                                                              console.log(
                                                                  "Not an owner"
                                                              )
                                                }
                                                data-key={usuario.id}
                                            >
                                                Actualizar
                                            </span>
                                        </td>
                                    </tr>)
                                })}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default Users;
