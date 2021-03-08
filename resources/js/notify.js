import Swal from 'sweetalert2';

const notify = {};

notify.toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 10000,
    timerProgressBar: true,
    showClass: {
        popup: '',
    },
    hideClass: {
        popup: ''
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});


notify.send = (type, msg, position = 'bottom-end') => {
    notify.toast.fire({
        position: position,
        icon: type,
        title: msg
    });
}

export default notify;
