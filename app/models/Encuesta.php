<?php

require '../vendor/autoload.php'; // Asegúrate de que este path es correcto según tu estructura de directorios

// Importar la clase FPDF
require_once "../vendor/setasign/fpdf/fpdf.php"; // Ajusta la ruta según la ubicación de FPDF en tu proyecto

require_once "./db/accesodatos.php";

class Encuesta
{
    public $id;
    public $idPedido;
    public $idMesa;
    public $puntuacion;
    public $comentario;

    public function __construct($idMesa, $idPedido, $puntuacion, $comentario)
    {
        $this->idMesa = $idMesa;
        $this->idPedido = $idPedido;
        $this->puntuacion = $puntuacion;
        $this->comentario = $comentario;
    }
    public function guardar()
    {
        $db = AccesoDatos::obtenerInstancia();
        $sql = "INSERT INTO encuestas (idMesa, idPedido, puntuacion, comentario) VALUES (:idMesa, :idPedido, :puntuacion, :comentario)";
        $consulta = $db->prepararConsulta($sql);
        $consulta->bindValue(':idMesa', (int)$this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', (int)$this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion', (int)$this->puntuacion, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->execute();
        return $db->obtenerUltimoId();
    }

    public static function buscarPorId($id)
    {
        $db = AccesoDatos::obtenerInstancia();
        $sql = "SELECT * FROM encuestas WHERE id = :id";
        $consulta = $db->prepararConsulta($sql);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_OBJ);
    }

    public static function obtenerMejoresEncuestas()
    {
        $cantidad = 5;
        $db = AccesoDatos::obtenerInstancia();
        $sql = "SELECT * FROM encuestas ORDER BY puntuacion DESC LIMIT :cantidad";
        $consulta = $db->prepararConsulta($sql);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPeoresEncuestas()
    {
        $cantidad = 5;
        $db = AccesoDatos::obtenerInstancia();
        $sql = "SELECT * FROM encuestas ORDER BY puntuacion ASC LIMIT :cantidad";
        $consulta = $db->prepararConsulta($sql);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function crearPDFMejoresEncuestas()
{
    $mejoresEncuestas = self::obtenerMejoresEncuestas();

    // Crear un nuevo objeto FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Configurar fuente y tamaño para el título
    $pdf->SetFont('Arial', 'B', 16);

    // Título del documento
    $pdf->Cell(0, 10, 'Mejores Encuestas', 0, 1, 'C');

    // Agregar imagen
    $pdf->Image('./Fotos/logo/elgordo.jpg', 100, 30, 60, 60); // Ajusta las coordenadas y el tamaño de la imagen según sea necesario

    // Configurar fuente para el contenido
    $pdf->SetFont('Arial', '', 12);

    // Iterar sobre las encuestas y agregarlas al PDF
    foreach ($mejoresEncuestas as $encuesta) {
        $pdf->Cell(0, 10, "Mesa: {$encuesta->idMesa} - Pedido: {$encuesta->idPedido} - Puntuacion: {$encuesta->puntuacion}", 0, 1);
        $pdf->MultiCell(0, 10, "Comentario: {$encuesta->comentario}", 0, 1);
    }

    // Guardar el PDF en el servidor (ajusta la ruta según tu configuración)
    $pdf->Output('./mejores_encuestas.pdf', 'F');
}

}
