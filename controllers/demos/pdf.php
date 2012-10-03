<?php
	class demos_pdf_controller extends controller {
		public function execute() {
			$pdf = new FPDF;
			$pdf->AddPage();
			$pdf->SetFont("helvetica", "B", 16);
			$pdf->Cell(0, 10, "Hello world!", 0, 1);
			$pdf->Ln();

			$pdf->SetFont("helvetica", "", 12);
			$pdf->SetFillColor(192, 192, 192);
			$pdf->Cell(40, 10, "Back", 1, 0, "C", 1);
			$pdf->Link(10, 30, 40, 10, "/demos");
			$pdf->Output();

			$this->output->disabled = true;
		}
	}
?>
