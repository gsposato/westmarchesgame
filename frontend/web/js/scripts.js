/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


function copyText(id) {
  btn = "#"+id+"-btn";
  txt = "#"+id;
  try {
      selectText(id);
      document.execCommand('copy');
      console.log("Text copied from ["+id+"]");
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-success");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Copied!');
  } catch (err) {
      $(btn).removeClass("btn-primary");
      $(btn).addClass("btn-danger");
      $(btn).html('<i class="fa fa-copy"></i>&nbsp;Failed!');
      console.log(err);
  }
}
function selectText(nodeId) {
    const node = document.getElementById(nodeId);

    if (document.body.createTextRange) {
        const range = document.body.createTextRange();
        range.moveToElementText(node);
        range.select();
    } else if (window.getSelection) {
        const selection = window.getSelection();
        const range = document.createRange();
        range.selectNodeContents(node);
        selection.removeAllRanges();
        selection.addRange(range);
    } else {
        console.warn("Could not select text in node: Unsupported browser.");
    }
}

$(function () {

    console.log('scripts.js loaded');

    $(document).on('click', '.game-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const url = $btn.attr('href') + "&js=true";
        const $icon = $btn.find('svg');
        $btn.addClass('disabled');
        $icon.removeClass().addClass('svg-inline--fa fa-spinner fa-spin');
        $.ajax({
            url: url,
            type: 'GET',
            success: function () {
                location.reload();
            },
            error: function () {
                alert('Something went wrong.');
                $icon.removeClass().addClass('svg-inline--fa fa-times');
                $btn.removeClass('disabled');
                $btn.addClass('danger');
            }
        });
    });

});
