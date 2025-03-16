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

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}