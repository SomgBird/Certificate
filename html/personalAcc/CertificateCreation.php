<?php
/*
 * (с) 2018 
 * 
 * Свообдно распространяется по следующим лицензиям (по вашему желанию):
 * - GPL 3.0
 * - GFDL 1.3
 * 
 *  Создание сертификатов
 */
 
# Подключение библиотеки TCPDF
require_once('/usr/share/doc/php-tcpdf/examples/tcpdf_include.php');
//require_once('tcpdf_include.php');

/**
 * Класс отвечающий за генерацию сертификата из шаблона.
 */
class CertificateCreation {
    // Глобальный список участников.
    private $participant_list = array();
    private $new_certificates = array();
    

    public function get_list()
    {
        return $this->participant_list;
    }

    public function get_new_cerf()
    {
        return $this->new_certificates;
    }

    /**
     * Добавляет участника в глобальный список, если данные введены корректно.
     * @param $participant строка вида "<имя> <фамилия>"
     */
    public function get_participant($participant) {
        // Отделяем имя от фамилии
        $p = explode(" ", $participant);

        // В каждой строке должны быть имя и фамилия
        if(count($p) >= 2) {
            // Убираем лишние пробелы
            $p[0] =  trim($p[0]);
            $p[1] =  trim($p[1]);
			$p[2] = trim($p[2]);               
            // Имя и фамилия не могут быть пустыми
            if (strlen($p[0]) == 0 || strlen($p[1]) == 0 || strlen($p[2]) == 0)
                return;
                
            $this->participant_list[] =  $p;
        }
    }

    /**
     * Добавляет в глобальный список участников всех участников из поля.
     * @param $files_list - содержимое поля с текстом.
     */
    public function get_list_from_field ($field_list) {
        $list = preg_split('/\r\n|[\r\n]/', $field_list);

        foreach ($list as $l)
            $this->get_participant($l);
    }


    /**
     * Получает список участников из файла и добавляет их в глобальный список участников.
     * @param $file_list файл со списком участников.
     */
    public function get_list_from_file ($file_list) {
        // Проверка существования файла
        if (!file_exists($file_list) && !isset($file_list)) {
            echo "нет файла";
            return;
        }

        $list = fopen($file_list, "r");

        // Для каждого участника в списке создаем сертификат
        while (($buffer = fgets($list)) !== false) {
            $this->get_participant($buffer);
        }
    }

    /**
     * Создает заготовки сертификата для всех участников из глобального списка
     */
    public function create_pdf_series($template_pic) {
        foreach ($this->participant_list as $participant)
            $this->create_pdf_blank ($template_pic, $participant[0], $participant[1]);
    }

    /**
     * Создает заготовку сертифката.
     * @param $template_pic картинка-шаблон.
     * @param $name имя.
     * @param $surname фамилия.
     */
     public function create_pdf_blank ($template_pic, $name, $surname) {
        // Создать новый PDF документ
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Задать отступы
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // Удалить footer по умолчанию
        $pdf->setPrintFooter(false);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Задаем шрифт
        $pdf->SetFont('dejavusans', '', 30, true);

        // Добавляем страницу
        $pdf->AddPage();

        // Отключаем автоматическое создание страницы
        $pdf->SetAutoPageBreak(false, 0);
        // Задаем фоновую картинку
        $pdf->Image($template_pic, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        
        // Точка начала контента
        $pdf->setPageMark();

        $pdf->SetXY(0, $pdf->getPageHeight() / 2);

        // Задаем шрифт
        $pdf->SetFont('dejavusans', '', 20, true);

        // Добавить текст с именем и фамилией
        $pdf->Cell($pdf->getPageWidth(), 20, $name . ' ' . $surname, 0, 1, 'C', 0, '', 0);

        // Задаем шрифт
        //$pdf->SetFont('dejavusans', '', 10, true);

	// Id транзакции
	$pdf->Cell($pdf->getPageWidth(), 10, 'ID транзакции: ', 0, 1, 'L', 0, '', 0);

	// Hash-сумма транзакции
	$pdf->Cell($pdf->getPageWidth(), 20, 'HASH-сумма транзакции: ', 0, 1, 'L', 0, '', 0);

        // Задаем шрифт
	//$pdf->SetFont('dejavusans', '', 20, true);

        // Добавить ссылку на страницу проверки
        $pdf->Cell(0, 20, 'test_check_page', 0, 1, 'C', 0, 'http://52.138.196.248/test_check_page.php', 0);
        
            
        // Генерируем имена файлов
        $path = $_SERVER["DOCUMENT_ROOT"] . 'personalAcc/pdf/';
        $file_name = "cerf" . date("YmdGsi");
        $count = 0;

        // Добавляем порядковый нормер копии, если такой файл уже есть
        while(file_exists($path . $file_name . $count . '.pdf')) {
            $count += 1;
        }
        
        $file_name .= "$count";

        // Сохраняем файл
        $pdf->Output($path . $file_name . '.pdf', 'F');
	
	$this->new_certificates[] = $path . $file_name . '.pdf';
    }
}
?>
