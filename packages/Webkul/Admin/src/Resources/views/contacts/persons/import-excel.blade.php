

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>page To Import Excel</title>
    <style>
        *{
            margin: 0; 
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;

        }
body{
  background-color: #f7f8f9;
  width: 100%;
  height: 100%;
  margin: 0;
}

main{
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
.header{
    padding: 0 10px;
}
.section{
    width: 40em;
    height: 50%;
    box-sizing: border-box;
    position: relative;
    background-color: #ececec;
    border-radius: 5px;
    margin: 0 20px;
    box-shadow: rgb(199, 199, 199) 2px 2px 2px 1px;
}
a{
    text-align: :right;
font-weight: 400;
font-size: 18px;
text-decoration: none;

}
.page-title-upload{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    
}
.page-title-upload > h1{
    font-size: 20px;
    font-weight: 500;
}
.description-upload{
    width: 100%;
    padding: 10px 20px;
}
.description-upload > p{
    text-align: center;
    
    font-weight:400;
    color: #444444;
    font-size: 15px;
}

form{
   width: 100%;
   height: 65%;
    display: flex;
    justify-items: center;
    flex-direction: column;
    align-items: center;
    padding: 0 10px;

}
.drag-drop-file{
    width: 100%;
    height: 80%;
    border: 1px dashed rgb(138, 138, 138);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 8px;
}
.drag-drop-file > h1{

font-weight: 600;
font-size: 16px;
}
.drag-drop-file >p{

font-weight: 600;
font-size: 12px;
color: #8d8d8d;
}
.drag-drop-file > label{
cursor: pointer;
    border-radius: 5px;
    padding: 5px 20px;
    display: block;
    font-size: 1em;

    font-weight: 600;

}
.color-button{
    font-weight: 600;
    border:1px solid rgb(95, 95, 95);
    color: rgb(59, 59, 59);
    background-color: #ececec;


} 
input{
display:none;

}
.section-btn{
    width: 100%;
    padding: 10px 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.section-btn >button{
    cursor: pointer;
    padding: 5px 20px;
        border-radius: 5px;
}

    </style>
</head>
<body >
    <main >
        <section class="section">
            <header class="header">
                <a href="{{route('admin.contacts.persons.index')}}"> x </a>
                <div class="page-title-upload">
                    <h1>Upload an Introduction</h1>
                </div>
                <div class="description-upload">
                    <p>
                        File Excel harus memiliki header <strong>name</strong>, <strong>emails</strong>, dan <strong>contact_numbers</strong> secara berurutan, dimulai dari kolom pertama. Pastikan data diisi sesuai urutan untuk diproses sistem.
                    </p>
                </div>

            </header>
          

            <form class="" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <section class="drag-drop-file" ondrop="uploadFileExcel(event)" ondragover="return false">
<h1 class="text-drop-excel">Drag and Drop Excel Files To Upload</h1>
<p>atau</p>
                    <label for="file_excel" class="btn-drag color-button" >Select File</label>
                    <input type="file" name="import_file_excel" id="file_excel" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                </section>
                <section class="section-btn">
                    <button type="submit" name="btn-submit" class="color-button">Submit</button>
                </section>
              </form>
        </section>
    </main>
    {{-- Jika Import Berhasil --}}
    @if(session('success'))
    <script>
    alert(`${{ session('success') }}`);
    </script>
                @endif
    {{-- Jika Import Error/Gagal --}}
    @if(session('error'))
    <script>
    alert(`${{ session('error') }}`);
    </script>
                @endif
    <script>
        const dropFile = document.querySelector('.drag-drop-file');
        const textDropFile = document.querySelector('.text-drop-excel');
        const buttonSumbit = document.querySelector('button[type="submit"]');
        const inputFileExcel = document.querySelector('input[name="import_file_excel"]');
        const TextNameFile = document.querySelector('label[for="file_excel"]');
        buttonSumbit.addEventListener('click', (e) => {
            // Validasi Submit File Jika File belum dimasukkan
            if(!inputFileExcel.files[0]) {
                alert("data belum dimasukkan");
                e.preventDefault();
            }else{
                const typeFile = ['xlsx','xls','csv'];
                const getTypeFile = inputFileExcel.files[0].name.split('.');
                if(!typeFile.includes(getTypeFile[getTypeFile.length-1])){
                    alert("Harap Yang dimasukkan adalah file excel");
                    inputFileExcel.value = "";
                    TextNameFile.textContent="Select File";
                    e.preventDefault();
                }
            };
        
        })

        // Action Drag & Drop File
        dropFile.addEventListener('dragover', (e)=>{
            e.preventDefault();
            textDropFile.textContent = "Relaese File Excel";
        });
        dropFile.addEventListener('dragleave', ()=>{
            textDropFile.textContent = "Drag and Drop Excel Files To Upload";
        });
        dropFile.addEventListener('drop', (e)=>{
            e.preventDefault();
            textDropFile.textContent = "Your'e Ready to Drop Excel Files Upload";
        });

        // Memasukkan Data Lewat Drop File & Menampilkan Nama File
        function uploadFileExcel(e){
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            inputFileExcel.files =  dataTransfer.files;
            TextNameFile.textContent = inputFileExcel.files[0].name;
        }
        //Memasukkan data lewat label input & Menampilkan Nama File
        inputFileExcel.addEventListener('change', () => {
            TextNameFile.textContent = inputFileExcel.files[0].name;
            
        })
    </script>
</body>
</html>