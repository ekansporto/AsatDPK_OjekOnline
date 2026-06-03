console.log('script.js loaded');

function validasi() {

    let nama = document.getElementById("nama").value;
    let jarak = document.getElementById("jarak").value;
    let voucher = document.getElementById("kodeVoucher").value;

    if (nama == "") {
        alert("Nama tidak boleh kosong!");
        return false;
    }

    let nohp = document.getElementById("nohp").value;
    let errorNoHp = document.getElementById("errorNoHp");

    if (nohp.length < 10) {
        errorNoHp.innerHTML = "Nomor HP minimal 10 digit";
        return false;
    } else {
        errorNoHp.innerHTML = "";
    }

    if (jarak <= 0) {
        alert("Jarak harus lebih dari 0 km!");
        return false;
    }

    else if (
        voucher != "" &&
        voucher != "HEMAT10" &&
        voucher != "HEMAT20" &&
        voucher != "HEMAT30"
    ) {
        alert("Kode voucher tidak valid!");
        return false;
    }

};

const nohpInput = document.getElementById("nohp");
const errorNoHp = document.getElementById("errorNoHp");
if (nohpInput) {
    nohpInput.addEventListener("keyup", function () {
        if (this.value.length < 10) {
            errorNoHp.innerHTML = "Nomor HP minimal 10 digit";
        } else {
            errorNoHp.innerHTML = "";
        }
    });
}
