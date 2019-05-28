const getCurrentLocation = new Promise((resolve, reject) => {
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            resolve({
                lon: position.coords.longitude,
                lat: position.coords.latitude
            });
        },error => {
            reject(locationError(error));
        });
    } else {
        reject("Geo Location not supported by browser");
    }
});

const watchLocation = (storage) => {
    navigator.geolocation.watchPosition(position => {
            storage.set('location', {
                lon: position.coords.longitude,
                lat: position.coords.latitude
            });
        },error => {
            storage.set('location', {
                error: locationError(error)
            });
        });
};

const get = (storage) => {
    return new Promise((resolve, reject) => {
        const geolocation = storage.get('location');
        if (geolocation) {
            if (geolocation.error) {
                reject(geolocation.error);
            } else if (geolocation.lat && geolocation.lon) {
                resolve(geolocation);
            }
        } else {
            Location.getCurrentLocation
                .then((device) => {
                    resolve(device);
                }).catch((err) => {
                    reject(err);
                });
        }
    });
};

const locationError = (error) => {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            return "Geolocation must be enabled.";
        case error.POSITION_UNAVAILABLE:
            return "Location information is unavailable.";
        case error.TIMEOUT:
            return "Location information request timed out.";
        case error.UNKNOWN_ERROR:
            return "An unknown error occurred.";
        default:
            return "";
    }
};

module.exports = {
    getCurrentLocation,
    watchLocation,
    get
};