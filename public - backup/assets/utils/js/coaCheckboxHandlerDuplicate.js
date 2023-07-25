(function() {
    const checkAll = document.querySelectorAll('[data-duplicate-checkall-trigger]');
    const checkAllData = document.querySelectorAll('[data-duplicate-checkall-checkbox]');

    function toggleCheckboxGroupDuplicate(checked, checkboxGroupDuplicate) {
        console.log(checkboxGroupDuplicate);
        let matchingCheckboxes = document.querySelectorAll('[data-duplicate-checkall-group="' + checkboxGroupDuplicate + '"]');
        matchingCheckboxes.forEach(function(el) {
            if (checked !== el.checked) {
                el.checked = !el.checked;
            }
        });
    };

    function toggleCheckboxAllData(checked, checkboxGroupDuplicate) {
        let matchingCheckboxes = document.querySelectorAll('[type="checkbox"]');
        matchingCheckboxes.forEach(function(el) {
            if (checked !== el.checked) {
                el.checked = !el.checked;
            }
        });
    };

    checkAll.forEach(function(el) {
        el.addEventListener('change', function() {
            let checkboxGroupDuplicate = el.dataset.duplicateCheckallTrigger;
            let checked = el.checked ? true : false;
            toggleCheckboxGroupDuplicate(checked, checkboxGroupDuplicate);
        });
    });
})();
$('.duplicate').on('click', function() {
    if(loaded === 0){
        var table = $('.datatable-import-duplicate').DataTable( {
            responsive: true
        } );

        loaded++;
    }
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('#selectallDuplicate').click(function () {
        console.log("kodok");
        var checkboxValue = $('#selectallDuplicate').is(':checked') ? 'on' : 'off';
        if(checkboxValue == "on"){
            $('.selectedIdDuplicate', rows).each(function(index) {
                if(index <= 300){
                    console.log(index)
                    $('.checkedDuplicate'+index, rows).prop('checked', true);
                }
            });
        }
        if(checkboxValue == "off"){
            $('.selectedIdDuplicate').each(function(index) {
                if(index <= 300){
                    $('.checkedDuplicate'+index, rows).prop('checked', false);
                }
            });
        }
    });

    // $('.selectedIdDuplicate').change(function () {
    //     var checkedCount = $('.selectedIdDuplicate:checked', rows).length;
    //     if(checkedCount == 3900){
    //         $('#selectallDuplicate').prop("checked", true);
    //     }
    //     if(checkedCount < 3900){
    //         $('#selectallDuplicate').prop("checked", false);
    //     }
    // });
    $('.selectedIdDuplicate').change(function () {
        var checkedCount = $('.selectedIdDuplicate:checked', rows).length;
        if(checkedCount == 3900){
            $('#selectall').prop("checked", true);
        }
        if(checkedCount < 3900){
            $('#selectall').prop("checked", false);
        }
    });
});

$('#frm-two-import').on('submit', function(e){
    var form = this;
    table.$('input[type="checkbox"]').each(function(){
       if(!$.contains(document, this)){
          if(this.checked){
             $(form).append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       }
    });
 });