// This code maybe not useful, please review code and delete this if not use anywhere
// my suggest use this instead settings.js because settings.js is very complicated

// $(document).ready(function() {
//     let index = 0; // شمارنده برای ایندکس
//
//     // رویداد کلیک روی دکمه "Add Section"
//     $(`.btn-sap-add-more-channel-setting`).click(function() {
//         const that = $(this);
//
//         const networkSectionId = `#` + that.data('network-section-id');
//         if(!index) {
//             index = parseInt(that.data('network-count'))
//         }
//         // کلون کردن section
//         let newSection = $(networkSectionId).clone();
//
//         // افزایش ایندکس
//         index++;
//
//         // تغییر نام اینپوت‌ها با ایندکس جدید
//         newSection.find('input').each(function() {
//             let name = $(this).attr('name');
//             name = name.replace(/\[([0-9]+)\]/, `[${index}]`); // جایگزینی ایندکس
//             $(this).attr('name', name);
//             $(this).val('');
//
//         });
//
//         newSection.find('textarea').each(function() {
//             let name = $(this).attr('name');
//             name = name.replace(/\[([0-9]+)\]/, `[${index}]`); // جایگزینی ایندکس
//             $(this).attr('name', name);
//             $(this).val('');
//
//         });
//
//         newSection.find('select').each(function() {
//             let name = $(this).attr('name');
//             name = name.replace(/\[([0-9]+)\]/, `[${index}]`); // جایگزینی ایندکس
//             $(this).attr('name', name);
//             $(this).val('');
//         });
//
//         // اضافه کردن section جدید به container
//         $(networkSectionId + "_container").append(newSection);
//     });
// });