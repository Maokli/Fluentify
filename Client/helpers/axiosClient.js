const instance = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
      'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODI3OTQ2OTIsImV4cCI6MTY4Mjc5ODI5Miwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoibG91YXlpbmZvMDdAZ21haWxjb20ifQ.cDQuuUQdVG99l9J-3EGrUM9jufUEPdNFFzSkFXOhNcWyXsGtczyGJr5aS2ETYNml7P-LmxzY6J0cb9woQuTS1aUwzy9oaNgeIhQV5YGaJmlMHS4jZf7WZDT6xsChXfancxB7FD92n6S2S-_y3SwweIyoEKosTACsIn5AflIOpm2hyhFkoI65nFrZmbCUbyEZomAwDfoX4Ey2K3mCwCmQ8NRVvFbbzzxn0kh26wx2NNXEl4xzJ4DCusS5MtYoGM0KzQigLJKUF2XW2YltuhMWumEZd__siKth68spdXDECoA0dd2BVDGkf3w2KKNmqzygjW6uzJxn_-PDRjX5eWiPNw',
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "POST, PUT, GET, DELETE",
      "Access-Control-Allow-Headers": "*",
      "Access-Control-Max-Age": "3600"
    }
});
export default instance;