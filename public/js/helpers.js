function formatDateNew(dateString) {
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const date = new Date(dateString); 
    const day = String(date.getDate()).padStart(2, '0');  
    const month = months[date.getMonth()]; 
    const year = date.getFullYear(); 

    return `${day} ${month} ${year}`;  
}


const formatDate = (date) => {
    const localDate = new Date(date);
    localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset()); 
    return localDate.toISOString().split('T')[0]; 
};

const formatDateLocal = (date) => {
  const localDate = new Date(date);
  return localDate.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  }).split('/').reverse().join('-'); 
};