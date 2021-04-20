import Index from '../views/Index';
import Login from '../views/Login';
import Categories from '../views/Categories';
import Dashboard from '../views/Dashboard';
import Articles from '../views/Articles';
import Users from '../views/Users';
import Register from '../views/Register';
import Archive from '../views/Archive';
import NoMatch from '../views/NoMatch';

const routes = [
  {
    path: '/',
    exact: true,
    auth: true,
    component: Dashboard,
    fallback: Index,
  },
  {
    path: '/articles',
    exact: true,
    auth: true,
    component: Articles,
  },
  {
    path: '/users',
    exact: true,
    auth: true,
    component: Users,
  },
  {
    path: '/categories',
    exact: true,
    auth: true,
    component: Categories,
  },
  {
    path: '/login',
    exact: true,
    auth: false,
    component: Login,
  },
  {
    path: '/register',
    exact: true,
    auth: false,
    component: Register,
  },
  {
    path: '/archive',
    exact: true,
    auth: true,
    component: Archive,
  },
  {
    path: '',
    exact: false,
    auth: false,
    component: NoMatch,
  },
];

export default routes;
