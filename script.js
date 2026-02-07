//display buat apa 
document.getElementById('login').addEventListener("click", function () {
    var formLogin = document.getElementsByClassName("form_after_login")[0];
    if (formLogin.style.display === 'none' || formLogin.style.display === ' ') { formLogin.style.display = 'block'; }
    else { formLogin.style.display = 'none' }
})

document.getElementById('urgent').addEventListener("click", function () {
    var form_login_2 = document.getElementsByClassName("form_after_login")[1];
    if (form_login_2.style.display === 'none' || form_login_2.style.display === '') {
        form_login_2.style.display = 'block';
    }
    else { form_login_2.style.display = 'block' }
})

document.getElementById('rutinan').addEventListener("click", function () {
    var page_rutinan = document.getElementById("rutin");
    if (page_rutinan.style.display === 'none' || page_rutinan.style.display === "") {
        page_rutinan.style.display = 'block';
    }
    else {
        page_rutinan.style.display = 'block'
    }
})

        const uang = document.getElementById('nomin');
        const uangMasuk = document.getElementById('uang_masuk');
        const uangKeluar = document.getElementById('uang_keluar');
        const sisa = document.getElementById('sisa_uang');

        document.getElementById('operasi').addEventListener('click', function() {
            if (uangMasuk.value === ''){
                uangMasuk.value = 0;
            }
            if (uangKeluar.value === ''){
                uangKeluar.value = 0;
            }
            const sisaUang = parseFloat(uang.textContent) - parseFloat(uangKeluar.value) + parseFloat(uangMasuk.value);
            sisa.textContent = sisaUang;
            document.getElementById('sisa_uang_input').value = sisaUang;
        })