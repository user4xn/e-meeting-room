(function() {
    const checkAll = document.querySelectorAll('[data-checkall-trigger]');
    const checkAllData = document.querySelectorAll('[data-checkall-checkbox]');

    function toggleCheckboxGroup(checked, checkboxGroup) {
        console.log(checkboxGroup);
        let matchingCheckboxes = document.querySelectorAll('[data-checkall-group="' + checkboxGroup + '"]');
        matchingCheckboxes.forEach(function(el) {
            if (checked !== el.checked) {
                el.checked = !el.checked;
            }
        });
    };

    function toggleCheckboxAllData(checked, checkboxGroup) {
        let matchingCheckboxes = document.querySelectorAll('[type="checkbox"]');
        matchingCheckboxes.forEach(function(el) {
            if (checked !== el.checked) {
                el.checked = !el.checked;
            }
        });
    };

    checkAll.forEach(function(el) {
        el.addEventListener('change', function() {
            let checkboxGroup = el.dataset.checkallTrigger;
            let checked = el.checked ? true : false;
            toggleCheckboxGroup(checked, checkboxGroup);
        });
    });
})();
var table = $('.datatable-import').DataTable( {
    responsive: true,
    pageLength: 300,
    dom: 'rtip'
} );
var delay = 1000;
var timeoutId;
$('#custom-search-import').on('keyup change', function() {
    clearTimeout(timeoutId);

    timeoutId = setTimeout(function() {
        var val = $('#custom-search-import').val();
        table.search(val).draw();
    }, delay);
});
$('#custom-row-import').on('change', function() {
    var val = $(this).val();
    table.page.len(val).draw();
});

$(document).ready(function () {
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('#selectall').click(function () {
        var checkboxValue = $('#selectall').is(':checked') ? 'on' : 'off';
        if(checkboxValue == "on"){
            $('.selectedId', rows).each(function(index) {
                if(index <= 300){
                    console.log(index);
                    $('.checked'+index, rows).prop('checked', true);
                }
            });
        }
        if(checkboxValue == "off"){
            $('.selectedId').each(function(index) {
                if(index <= 300){
                    $('.checked'+index, rows).prop('checked', false);
                }
            });
        }
    });
    $('.selectedId').change(function () {
        var checkedCount = $('.selectedId:checked', rows).length;
        if(checkedCount == 3900){
            $('#selectall').prop("checked", true);
        }
        if(checkedCount < 3900){
            $('#selectall').prop("checked", false);
        }
    });
});

$('#frm-one-import').on('submit', function(e){
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