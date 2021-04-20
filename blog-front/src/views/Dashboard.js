import React, { useState, useEffect } from "react";
import Http from "../Http";
import { Link } from 'react-router-dom';

const api = "http://localhost/blog-react-yii2-mysql/blog-back/web/crud/articles";

const plantilla = require("../storage/default.png");

const Dashboard = () => {
    const [dataState, setData] = useState([]);
    const [error, setError] = useState(false);

    useEffect(() => {
        Http.get(api)
            .then((response) => {
                const data  = response.data.items;
                if(data.length > 0 ){
                    setData(data);
                }
                setError(false);
            })
            .catch((err) => {
                console.log(err);
            });
    }, []);

    return (
        <div className="container">            
            <h1 className="text-center">Artículos</h1>
            <p>Todos los artículos: <b>{dataState.length}</b></p>
            {dataState.map((article) => {
                return(
                    <div className="row" key={article.id}>
                        <div className="col">
                            <img src={plantilla} alt="prueba"/>
                        </div>
                        <div className="col">
                            <h1>{article.titulo}</h1>
                            <h6><b>Slug:</b> <Link to="/">{article.slug}</Link></h6>
                            <h3>{article.texto_corto}</h3>
                            <p>{article.texto_largo}</p>
                            <p className="text-right"><i>Categoria: <b>{article.cat_id.label}</b></i></p>
                            <p className="text-right"><i>Fecha de creación <b>{article.fecha_creacion}</b></i></p>
                            <p className="text-right"><i>Fecha de actualización <b>{article.fecha_actualizacion}</b></i></p>
                        </div>
                    </div>
                    )
                })}
        </div>
    );
};

export default Dashboard;
