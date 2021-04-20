import React from "react";

const Alert = () => {
    return (
        <div className="alert alert-info" role="alert">
            <h4 className="alert-heading">Bienvenido!</h4>
            <p>Seleccione un artículo de la columna de la derecha.</p>
            <hr />
            <p className="mb-0">También puedes hacer tus propios artículos!</p>
        </div>
    );
};

export default Alert;
