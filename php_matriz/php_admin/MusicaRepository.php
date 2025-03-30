<?php
class MusicaRepository {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Buscar todas as músicas com seus dados
    public function getAllMusicas() {
        $sql_musica = "SELECT id_musica, nomeMusica, tempo FROM Musica";
        $result = $this->conn->query($sql_musica);
        $musicas = [];
        
        while ($musica = $result->fetch_assoc()) {
            $musicas[] = $musica;
        }
        return $musicas;
    }

    // Buscar os CDs associados a uma música
    public function getCdsByMusicaId($id_musica) {
        $sql_cds = "SELECT CD.titulo 
                    FROM CD_Musica 
                    JOIN CD ON CD_Musica.id_cd = CD.id_cd 
                    WHERE CD_Musica.id_musica = ?";
        $stmt = $this->conn->prepare($sql_cds);
        $stmt->bind_param("i", $id_musica);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cds = [];
        while ($cd = $result->fetch_assoc()) {
            $cds[] = $cd['titulo'];
        }
        
        return $cds;
    }

    // Puxar o caminho do áudio da pasta
    public function getAudioPath($id_musica) {
        $audio_file = "audio/musica" . $id_musica . ".mp3"; // Exemplo de nome de arquivo
        if (file_exists($audio_file)) {
            return $audio_file;
        }
        return null;
    }
}
?>