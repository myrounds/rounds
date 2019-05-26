const init = (axios, storage, context) => {
    const api = axios.create();
    const user = storage.get('user');

    if (user) {
        api.defaults.headers.common['Authorization'] =  'Bearer ' + user.token;
    }
    axios.interceptors.response.use(null, (error) => {
        if(!error.response ||
            !error.response.status) { }
        if (error.response) {
            switch(error.response.status) {
                case 500:
                    context.$msg('Server error');
                    break;
                case 401:
                    context.$msg('Unauthorized');
                    break;
                case 404:
                    context.$msg('Not found');
                    break;
            }
        }
        return Promise.reject(error);
    });
};

module.exports = {
    init
};