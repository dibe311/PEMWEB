let patients = [];

document.addEventListener("DOMContentLoaded", () => {
    loadFromLocalStorage();
    
    const form = document.getElementById("patientForm");
    if(form) form.addEventListener("submit", savePatient);

    const searchInput = document.getElementById("searchInput");
    if(searchInput) {
        searchInput.addEventListener("input", searchPatient);
    }
});

function loadFromLocalStorage() {
    const storedData = localStorage.getItem("patients");
    if (storedData) {
        patients = JSON.parse(storedData);
    }
    displayPatients(patients); 
}

function saveToLocalStorage() {
    localStorage.setItem("patients", JSON.stringify(patients));
}

function searchPatient(event) {
    const keyword = event.target.value.toLowerCase().trim();

    const filteredPatients = patients.filter(patient => {
        return patient.nama.toLowerCase().includes(keyword);
    });

    displayPatients(filteredPatients);
}

function displayPatients(dataToRender = patients) {
    const tableBody = document.getElementById("tableBody");
    if (!tableBody) return;

    tableBody.innerHTML = ""; 

    if (dataToRender.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">Tidak ada data pasien ditemukan.</td></tr>`;
        return;
    }

    dataToRender.forEach(patient => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${patient.id}</td>
            <td>${patient.nama}</td>
            <td>${patient.umur}</td>
            <td>${patient.jenisKelamin}</td>
            <td>${patient.keluhan}</td>
            <td>${patient.diagnosa}</td>
            <td>
                <button onclick="editPatient('${patient.id}')">Edit</button>
                <button onclick="deletePatient('${patient.id}')">Hapus</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function savePatient(event) {
    event.preventDefault();
    
    const idInput = document.getElementById("patientId")?.value;
    
    const newPatient = {
        id: idInput ? idInput : "RM-" + Date.now(), 
        nama: document.getElementById("nama").value,
        umur: document.getElementById("umur").value,
        jenisKelamin: document.getElementById("jenisKelamin").value,
        keluhan: document.getElementById("keluhan").value,
        diagnosa: document.getElementById("diagnosa").value
    };

    if (idInput) {
        const index = patients.findIndex(p => p.id === idInput);
        if (index !== -1) patients[index] = newPatient;
    } else {
        patients.push(newPatient);
    }

    saveToLocalStorage();
    displayPatients(patients); 
    event.target.reset();
    if(document.getElementById("patientId")) document.getElementById("patientId").value = "";
}

function deletePatient(id) {
    if (confirm("Yakin ingin menghapus data pasien ini?")) {
        patients = patients.filter(patient => patient.id !== id);
        
        saveToLocalStorage();
        
        const searchInput = document.getElementById("searchInput");
        if (searchInput && searchInput.value !== "") {
            searchInput.dispatchEvent(new Event("input"));
        } else {
            displayPatients(patients);
        }
    }
}

function editPatient(id) {
    const patient = patients.find(p => p.id === id);
    if (!patient) return;

    document.getElementById("patientId").value = patient.id;
    document.getElementById("nama").value = patient.nama;
    document.getElementById("umur").value = patient.umur;
    document.getElementById("jenisKelamin").value = patient.jenisKelamin;
    document.getElementById("keluhan").value = patient.keluhan;
    document.getElementById("diagnosa").value = patient.diagnosa;
}