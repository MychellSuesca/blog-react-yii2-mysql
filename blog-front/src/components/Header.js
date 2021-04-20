import React from 'react'
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import {
  Nav,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem,
} from 'reactstrap';
import * as actions from '../store/actions';


const Header = (props) => {
  let isAdmin = JSON.parse(window.localStorage.getItem("is_admin"));
  const handleLogout = (e) => {
      e.preventDefault();
      props.dispatch(actions.authLogout());
    };

  return (
    <header className="d-flex align-items-center justify-content-between">
    <h2 className="logo my-0">
      <Link to="/">APLICACIÓN BLOG</Link>
    </h2>

    {props.isAuthenticated && (
      <div className="navigation d-flex justify-content-end">
        <Nav>
          {isAdmin && 
          <UncontrolledDropdown nav inNavbar>
            <DropdownToggle nav caret>
              Configuración
            </DropdownToggle>
            <DropdownMenu right>
              <DropdownItem as={Link} href="/articles" >Articulos</DropdownItem>
              <DropdownItem divider />
              <DropdownItem as={Link} href="/users">Usuarios</DropdownItem>
              <DropdownItem divider />
              <DropdownItem as={Link} href="/categories" >Categorias</DropdownItem>
            </DropdownMenu>
          </UncontrolledDropdown>
          }
          <UncontrolledDropdown nav inNavbar>
            <DropdownToggle nav caret>
              Cuenta
            </DropdownToggle>
            <DropdownMenu right>
              <DropdownItem onClick={handleLogout}>
                Cerrar Sesión
              </DropdownItem>
            </DropdownMenu>
          </UncontrolledDropdown>
        </Nav>
      </div>
    )}
  </header>
  )
}

const mapStateToProps = (state) => ({
  isAuthenticated: state.Auth.isAuthenticated,
});

export default connect(mapStateToProps)(Header);
