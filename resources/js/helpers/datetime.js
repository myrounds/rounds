const getDaysOfWeek = () => {
    return ["monday","tuesday","wednesday","thursday","friday","saturday","sunday"];
};

const getCurrentDay = () => {
    const date = new Date();
    const dayIndex = date.getDay() - 1;

    console.log();
    return getDaysOfWeek()[dayIndex >= 0 ? dayIndex : 6];
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