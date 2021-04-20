import React, { useState, useEffect } from "react";
import Http from "../Http";
import { useForm } from "react-hook-form";
import Dropdown from "../components/DropdownCat";
import swal from "sweetalert";

const api = "http://localhost/blog-react-yii2-mysql/blog-back/web/crud/articles";

let options = [
    
];

const getCategorias = (hook) => {
    //Get Categories
    Http.get("http://localhost/blog-react-yii2-mysql/blog-back/web/crud/categories")
        .then((response) => {
            if(response.data.items.length > 0 ){
                response.data.items.map(item => (
                    options.push({"value":String(item.id), "label":item.categoria}) 
                ));
            }
            hook(options);
        })
        .catch((err) => {
            console.log(err);
    });
}

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

const Articles = () => {
    const session = JSON.parse(window.localStorage.getItem("user"));
    const { id } = session;
    const { register, handleSubmit } = useForm();
    const [dataState, setData] = useState([]);
    const [categoriasState, setCategoria] = useState([]);
    const [error, setError] = useState(false);
    const [stateForm, setStateForm] = useState({
        titulo: "",
        slug: "",
        texto_corto: "",
        texto_largo: "",
        imagen: "",
        fecha_creacion: "",
        fecha_actualizacion: "",
        cat_id: {},
    });

    useEffect(() => {
        getCategorias(setCategoria);
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
        addArticle(stateForm);
    };

    const addArticle = (article) => {
        if (article?.id) {
            Http.post(api+"/save/"+ article.id, article)
                .then((response) => {
                    getData(setData);
                    setStateForm({
                        titulo: "",
                        slug: "",
                        texto_corto: "",
                        texto_largo: "",
                        imagen: "",
                        fecha_creacion: "",
                        fecha_actualizacion: "",
                        cat_id: {},
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar el artículo");
                });
        } else {
            Http.post(api+"/save", article)
                .then(({ data }) => {
                    getData(setData);
                    setStateForm({
                        titulo: "",
                        slug: "",
                        texto_corto: "",
                        texto_largo: "",
                        imagen: "",
                        fecha_creacion: "",
                        fecha_actualizacion: "",
                        cat_id: {},
                    });
                    setError(false);
                })
                .catch(() => {
                    setError("Hubo un error al intentar guardar el artículo");
                });
        }
    };

    const editArticle = (article) => {
        const { id } = article;
        let form = dataState.filter((art) => art.id === id);
        setStateForm(form[0]);
    };

    const deleteArticle = (e) => {
        const { key } = e.target.dataset;
        swal({
            title: "Estas seguro?",
            text: "Una vez eliminado, no puede volver a ver este artículo!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                Http.delete(`${api}/delete/${key}`)
                .then((response) => {
                    getData(setData);
                    swal("El artículo ha sido eliminado!", {
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
                        Agregar/Actualizar un Articulo
                    </h1>

                    <form method="post" onSubmit={handleSubmit(onSubmit)}>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <input
                                id="titulo"
                                type="text"
                                name="titulo"
                                className="form-control"
                                placeholder="Título"
                                required
                                onChange={handleChange}
                                value={stateForm.titulo}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                        <div className="form-group col-md-6">
                            <input
                                id="slug"
                                name="slug"
                                className="form-control"
                                placeholder="Slug"
                                maxLength={50}
                                onChange={handleChange}
                                value={stateForm.slug}
                                ref={register()}
                            />
                        </div>
                    </div>
                    <div className="form-row">
                         <div className="form-group col-md-6">
                            <input
                                id="texto_corto"
                                type="text"
                                name="texto_corto"
                                className="form-control"
                                placeholder="Texto Corto"
                                required
                                onChange={handleChange}
                                value={stateForm.texto_corto}
                                maxLength={50}
                                minLength={5}
                                ref={register({ required: true })}
                            />
                        </div>
                        <div className="form-group col-md-6">
                            <textarea
                                id="texto_largo"
                                name="texto_largo"
                                required
                                maxLength={5000}
                                minLength={20}
                                className="form-control"
                                placeholder="Contenido"
                                onChange={handleChange}
                                value={stateForm.texto_largo}
                                ref={register()}
                            />
                        </div>
                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <input
                                id="imagen"
                                type="url"
                                name="imagen"
                                maxLength={100}
                                className="form-control"
                                placeholder="Imagen"
                                onChange={handleChange}
                                value={stateForm.imagen}
                                ref={register()}
                            />
                        </div>
                        <div className="form-group col-md-6">
                            <Dropdown
                                name="cat_id"
                                title={
                                    stateForm?.cat_id?.label ??
                                    "Categoria"
                                }
                                options={categoriasState}
                                setStateForm={setStateForm}
                                stateForm={stateForm}
                            />
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
                    <h1 className="text-center">Lista de Articulos</h1>
                    <table className="table">
                        <tbody>
                            <tr>
                                <th>Título</th>
                                <th>Slug</th>
                                <th>Texto Corto</th>
                                <th>Contenido</th>
                                <th>Categoria</th>
                                <th>Imagen</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
                                <th colSpan="2">Acciones</th>
                            </tr>
                            {dataState.map((article) => {
                                return(
                                    <tr key={article.id}>
                                        <td>{article.titulo}</td>
                                        <td>{article.slug}</td>
                                        <td>{article.texto_corto}</td>
                                        <td>{article.texto_largo}</td>
                                        <td>{article.cat_id?.label}</td>
                                        <td>{article.imagen}</td>
                                        <td>{article.fecha_creacion}</td>
                                        <td>{article.fecha_actualizacion}</td>
                                        <td>
                                            <span
                                                type="button"
                                                className="badge badge-danger"
                                                onClick={
                                                    article.user_id === id
                                                        ? deleteArticle
                                                        : () =>
                                                              console.log(
                                                                  "Not an owner"
                                                              )
                                                }
                                                data-key={article.id}
                                            >
                                                Eliminar
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                type="button"
                                                className="badge badge-dark"
                                                onClick={
                                                    article.user_id === id
                                                        ? () =>
                                                              editArticle(
                                                                  article
                                                              )
                                                        : () =>
                                                              console.log(
                                                                  "Not an owner"
                                                              )
                                                }
                                                data-key={article.id}
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

export default Articles;
