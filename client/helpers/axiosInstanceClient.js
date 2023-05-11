import { showLoader, hideLoader } from "./loader.js";

const axiosInstance = axios.create({
    baseURL: 'https://fluentify.onrender.com/api',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "POST, PUT, GET, DELETE",
      "Access-Control-Allow-Headers": "*",
      "Access-Control-Max-Age": "3600"
    }
});

axiosInstance.interceptors.request.use((config) => {
  showLoader();
  return config;
});
axiosInstance.interceptors.response.use(
  (response) => {
      hideLoader();
      return Promise.resolve(response);
  },
  (error) => {
      hideLoader();
      
    if(error.response.status === 401) {
      window.location.href = 'login.html';
    }
      return Promise.reject(error);
  },
);


export default axiosInstance;