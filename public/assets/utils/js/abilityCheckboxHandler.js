function handleCheckboxClick(prefix, bx) {
    const viewCheckbox = document.getElementById(prefix+'View');
    const createCheckbox = document.getElementById(prefix+'Create');
    const editCheckbox = document.getElementById(prefix+'Edit');
    const deleteCheckbox = document.getElementById(prefix+'Delete');
    const verifyCheckbox = document.getElementById(prefix+'Verify');
    const masterCheckbox = document.getElementById(prefix+'Master');

    if (bx.id === prefix+'Master') {
        if(masterCheckbox.checked == true){
            viewCheckbox.checked = true;
            createCheckbox.checked = true;
            editCheckbox.checked = true;
            deleteCheckbox.checked = true;
            verifyCheckbox.checked = true;
        } else {
            viewCheckbox.checked = false;
            createCheckbox.checked = false;
            editCheckbox.checked = false;
            deleteCheckbox.checked = false;
            verifyCheckbox.checked = false;
        }
    } else {
        if (!viewCheckbox.checked && !createCheckbox.checked && !editCheckbox.checked && !deleteCheckbox.checked && !verifyCheckbox.checked) {
            masterCheckbox.checked = false;
        } else {
            masterCheckbox.checked = true;
        }
    }
}