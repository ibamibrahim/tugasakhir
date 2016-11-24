<?php
require_once "database.php";


echo generateTable(1,100,10);

function generateTable($page,$totalData,$datasperPage){

    $bottom = ($page-1)*$datasperPage+ 1;
    $top = $page*$datasperPage;

    $db = new database();
    $conn = $db->connectDB();
    $stmt = $conn->prepare("SELECT mh.nama,jm.namamks,mks.judul,js.tanggal,js.jammulai,js.jamselesai,dp.nipdosenpenguji,dpem.nipdosenpembimbing
FROM jadwal_sidang js NATURAL JOIN mata_kuliah_spesial mks NATURAL JOIN mahasiswa mh JOIN jenis_mks jm ON mks.idjenismks = jm.id  NATURAL LEFT OUTER JOIN dosen_pembimbing dpem NATURAL LEFT OUTER JOIN dosen_penguji dp
WHERE dp.nipdosenpenguji=:nip OR dpem.nipdosenpembimbing =:nip AND RowNum >=:st AND RowNum <=:ed
ORDER BY js.tanggal DESC,js.jammulai;");
    $stmt->execute(array(':nip' =>$_SESSION['userdata']['nip'],':st' =>$bottom, ':ed'=>$top));
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = "<table><thead><tr>";

    $columnName = array('aa','aaa','aaaa');
    foreach ($columnName as $th){
        $html = $html."<th>".$th." </th>";
    }
    $html = $html."</thead></tr>";

    foreach ($datas as $key => $dataRow){
        $html = $html."<tr>";

        echo "1";

        $html = $html."<td>".$dataRow["mahasiswa"]."</td>".
            "<td>".$dataRow['namamks']."</td>".
            "<td>".$dataRow['judul']."</td>"
        ;

        //TODO add waktu dan lokasi

        //TODO add Dospem lain


        $res ="";
        if($dataRow['izinmasuksidang'] == true){
            $res=$res."Izin Masuk Sidang";
        }
        if($dataRow['kumpulhardcopy'] == true){
            $res=$res."Kumpul Hard Copy";
        }

        $html = $html."<td>".$res."</td>";


        $html = $html."</tr>";
    }
    $html = $html."</table>";
    return $html;
}

?>