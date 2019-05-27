const getDaysOfWeek = () => {
    return ["sunday","monday","tuesday","wednesday ","thursday","friday","saturday"];
};

const getCurrentDay = () => {
    const date = new Date();
    const dayIndex = date.getDay();

    return getDaysOfWeek()[dayIndex];
};

const getCurrentDate = () => {
    const today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth() + 1; //January is 0!

    const yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    return dd + '/' + mm + '/' + yyyy;
};


module.exports = {
    getDaysOfWeek,
    getCurrentDay,
    getCurrentDate
};