$(document).ready(function () {
  // let checkBoxes = $('.checkbox-template.-notify'),
  //   appNotifBtn = $('#approve-notification-btn'),
  //   notifyAllCheckbox = $('#notify-all'),
  //   disableClass = 'd-none',
  //   checkedClass = 'checked'
  //
  // //default settings
  // checkBoxes.prop('checked', false)
  // notifyAllCheckbox.prop('checked', false).removeClass(checkedClass)
  //
  // //notification checkboxes management
  // notifyAllCheckbox.on('click', function () {
  //   if (notifyAllCheckbox.hasClass(checkedClass)) {
  //     checkBoxes.prop('checked', false)
  //     appNotifBtn.addClass(disableClass)
  //   } else {
  //     checkBoxes.prop('checked', true)
  //     appNotifBtn.removeClass(disableClass)
  //   }
  //
  //   notifyAllCheckbox.toggleClass(checkedClass)
  // })
  //
  // //notify all button visibility management
  // checkBoxes.on('change', function () {
  //   let display = false
  //
  //   checkBoxes.each(function() {
  //     if ($(this).prop('checked')) {
  //       display = true
  //
  //       //exit from loop
  //       return false
  //     }
  //   })
  //
  //   if (display) {
  //     appNotifBtn.removeClass(disableClass)
  //   } else {
  //     appNotifBtn.addClass(disableClass)
  //     notifyAllCheckbox.prop('checked', false)
  //     notifyAllCheckbox.toggleClass(checkedClass)
  //   }
  // })
  //
  // appNotifBtn.on('click', function() {
  //   let personsIds = []
  //
  //   checkBoxes.each(function() {
  //     let checkBox = $(this)
  //
  //     if (checkBox.prop('checked')) {
  //       personsIds.push(checkBox.attr('data-id'))
  //     }
  //   })
  //
  //   $('#notify-qty').html(personsIds.length)
  //   $('#form_participants').val(JSON.stringify(personsIds))
  // })

  $('.approve-notification-btn').on('click', function() {
    $('#finally-notify').attr('href', $(this).attr('data-link'))
  })
})