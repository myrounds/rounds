const get = (key) => {
    const stored = window.localStorage.getItem(key);

    if (stored && typeof stored === 'string') {
        const parsed = JSON.parse(stored);

        if (typeof parsed != null &&
            typeof parsed === 'object') {
            return parsed;
        }
    }
    return null;
};

const set = (key, value) => {
    window.localStorage.setItem(key, JSON.stringify(value));
};

const clear = (key) => {
    window.localStorage.removeItem(key);
};

module.exports = {
    get,
    set,
    clear
};