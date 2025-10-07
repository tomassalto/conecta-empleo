import axios from "axios";

export const api = axios.create({
  baseURL: "/", // gracias al proxy, /api va a laravel
  withCredentials: false, // para listado público no hace falta cookie
});
