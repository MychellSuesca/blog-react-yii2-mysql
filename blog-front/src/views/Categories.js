import React, { useState, useEffect } from "react";
import Http from "../Http";
import { useForm } from "react-hook-form";
import swal from "sweetalert";

const api = "http://localhost/blog-react-yii2-mysql/blog-back/web/crud/categories";

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

const Categories = () => {
    const session = JSON.parse(window.localStorage.getItem("user"));
    const { id } = session;
    const { register, handleSubmit } = useForm();
    const [dataState, setData] = useState([]);
    const [error, setError] = useState(false);
    const [stateForm, setStateForm] = useState({
        categoria: "",
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
        addcategoria(stateForm);
    };

    const addcategoria = (categoria) => {
        if (categoria?.id) {
            Http.post(api+"/save/"+ categoria.id, categoria)
                .then((response) => {
                    getData(setData);
                    setStateForm({
                        categoria: "",
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar la categoria");
                });
        } else {
            Http.post(api+"/save", categoria)
                .then(({ data }) => {
                    getData(setData);
                    setStateForm({
                        categoria: "",
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar la categoria");
                });
        }
    };

    const editcategoria = (categoria) => {
        const { id } = categoria;
        let form = dataState.filter((cat) => cat.id === id);
        setStateForm(form[0]);
    };

    const deletecategoria = (categoria) => {
        const { key } = categoria.target.dataset;
        swal({
            title: "Estas seguro?",
            text: "Una vez eliminado, no puede volver a ver la categoria!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                Http.delete(`${api}/delete/${key}`)
                .then((response) => {
                    getData(setData);
                    setStateForm({
                        categoria: "",
                    });
                    setError(false);
                    swal("la categoria ha sido eliminado!", {
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
                <div className="col-md-4">
                    <h1 className="text-center mb-4">
                        Agregar/Actualizar una Categoria
                    </h1>
                    <form method="post" onSubmit={handleSubmit(onSubmit)}>
                    <div className="form-row">
                        <input
                            id="categoria"
                            type="categoria"
                            name="categoria"
                            className="form-control"
                            placeholder="Categoria"
                            required
                            onChange={handleChange}
                            value={stateForm.categoria}
                            maxLength={100}
                            minLength={1}
                            ref={register({ required: true })}
                        />
                    </div>
                    <div className="p-1 mb-1">
                        <div className="text-center">
                            <button
                                type="submit"
                                className="btn btn-block btn-outline-primary"
                            >
                                Agregar
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
                <div className="col-md-8">
                    <h1 className="text-center mb-4">Lista de Categorias</h1>
                        <table className="table">
                            <tbody>
                                <tr>
                                    <th>Nombre</th>
                                    <th colSpan="2">Acciones</th>
                                </tr>
                                {dataState.map((categoria) => {
                                    return(
                                        <tr key={categoria.id}>
                                            <td>{categoria.categoria}</td>
                                            <td>
                                                <span
                                                    type="button"
                                                    className="badge badge-danger"
                                                    onClick={
                                                        categoria.user_id === id
                                                            ? deletecategoria
                                                            : () =>
                                                                  console.log(
                                                                      "Not an owner"
                                                                  )
                                                    }
                                                    data-key={categoria.id}
                                                >
                                                    Eliminar
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    type="button"
                                                    className="badge badge-dark"
                                                    onClick={
                                                        categoria.user_id === id
                                                            ? () =>
                                                                  editcategoria(
                                                                      categoria
                                                                  )
                                                            : () =>
                                                                  console.log(
                                                                      "Not an owner"
                                                                  )
                                                    }
                                                    data-key={categoria.id}
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

export default Categories;
