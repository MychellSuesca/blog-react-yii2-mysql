import React, { useState, useEffect } from "react";
import Http from "../Http";
import { Link } from 'react-router-dom';
import plantilla from '../storage/default.png';
import { HandThumbsUp, HandThumbsUpFill} from 'react-bootstrap-icons';

const api = "http://localhost/blog-react-yii2-mysql/blog-back/web/crud/articles";

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

const Dashboard = () => {
    const [dataState, setData] = useState([]);
    const [error, setError] = useState(false);

    useEffect(() => {
        getData(setData);
    }, []);

    const like = (e) => {
        let user = JSON.parse(window.localStorage.getItem("user"));
        Http.post(api+"/like/"+e+"/"+user)
            .then((response) => {
                getData(setData);
            })
            .catch((error) => {
                console.log(error)
            });
    }

    return (
        <div className="container">            
            <h1 className="text-center">Artículos</h1>
            <p>Todos los artículos: <b>{dataState.length}</b></p>
            {dataState.map((article) => {
                return(
                    <div className="row" key={article.id}>
                        <div className="col text-right">
                            <img src={plantilla} alt="prueba" style={{
                                height: 400,
                                width: 400,
                            }}/>
                        </div>
                        <div className="col">
                            <h1>{article.titulo}</h1>
                            <h6><b>Slug:</b> <Link to="/">{article.slug}</Link></h6>
                            <h3>{article.texto_corto}</h3>
                            <p>{article.texto_largo}</p>
                            <span type="button" onClick={()=>like(article.id)}><HandThumbsUp size={40} />{article.likes}</span>
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
