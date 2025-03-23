document.addEventListener('DOMContentLoaded', function() {
    const picker = new tempusDominus.TempusDominus(document.getElementById('monthYearPicker'), {
        display: {
            viewMode: 'months',
            components: {
                decades: false,
                year: false,
                date: false,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        localization: {
            format: 'MMMM/yyyy' // Format as Month/Year
        }
    });
});
