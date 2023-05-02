const axiosInstance = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "POST, PUT, GET, DELETE",
      "Access-Control-Allow-Headers": "*",
      "Access-Control-Max-Age": "3600"
    }
});
export default axiosInstance;