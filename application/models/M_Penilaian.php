<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Penilaian extends CI_Model {

	public function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('penilaian');
		$this->db->join('karyawan AS dinilai','dinilai.ID_KARYAWAN = penilaian.KAR_ID_KARYAWAN');
		$this->db->join('jabatan','dinilai.ID_JABATAN = jabatan.ID_JABATAN');
		$this->db->where('ID_PENILAIAN',$id);
		return $this->db->get();

	}

	public function cek_penilaian($kar_id_karyawan,$periode){
/*		$this->db->select('*');
		$this->db->from('penilaian');
		$this->db->join('periode_kehadiran_dan_penilaian','penilaian.ID_PERIODE2 = periode_kehadiran_dan_penilaian.ID_PERIODE2','inner');
		$this->db->join('karyawan','penilaian.KAR_ID_KARYAWAN = karyawan.ID_KARYAWAN','inner');
		$this->db->where('ID_PERIODE2',$periode);
		$this->db->where('KAR_ID_KARYAWAN',$kar_id_karyawan);
		$this->db->limit(1);
		return $this->db->get();
*/
		$query = $this->db->query("
			SELECT
				*
			FROM
				penilaian
			INNER JOIN periode_kehadiran_dan_penilaian ON penilaian.ID_PERIODE2 = periode_kehadiran_dan_penilaian.ID_PERIODE2
			INNER JOIN karyawan ON penilaian.KAR_ID_KARYAWAN = karyawan.ID_KARYAWAN
			WHERE
				penilaian.ID_PERIODE2 = ".$periode." AND
				penilaian.KAR_ID_KARYAWAN = '".$kar_id_karyawan."'
			LIMIT 1
		");
		return $query; 
	}

	public function cek_riwayat($id){
		$this->db->select('*');
		$this->db->from('penilaian');
		$this->db->join('periode_kehadiran_dan_penilaian','penilaian.ID_PERIODE2 = periode_kehadiran_dan_penilaian.ID_PERIODE2');
		$this->db->where('KAR_ID_KARYAWAN',$id);
		return $this->db->get();
	}

	public function create($id,$periode,$karyawan,$kar,$ket,$nilai){
		return $this->db->insert(
			'penilaian',
			array(
				'ID_PENILAIAN' => $id,
				'ID_PERIODE2' => $periode,
				'ID_KARYAWAN' => $karyawan,
				'KAR_ID_KARYAWAN' => $kar,
				'KETERANGAN_PENILAIAN' => $ket,
				'PENILAIAN_TOTAL' => $nilai
			)
		);
	}

	public function get_by_karyawan_periode($karyawan,$periode)
	{
		$this->db->select('*');
		$this->db->from('penilaian');
		$this->db->where('KAR_ID_KARYAWAN',$karyawan);
		$this->db->where('ID_PERIODE2',$periode);
		return $this->db->get();
	}

	public function get_id_for_laporan($id)
	{
		return $this->db->query('
			SELECT
				karyawan.NAMA_KARYAWAN AS KARYAWAN_DINILAI,
				penilai.NAMA_KARYAWAN AS PENILAI,
				jabatan.NAMA_JABATAN AS JABATAN,
				penilaian.KAR_ID_KARYAWAN AS NIK,
				PENILAIAN_TOTAL,
				ID_PERIODE2,
				KAR_ID_KARYAWAN
			FROM
				penilaian
			INNER JOIN karyawan AS penilai ON penilaian.ID_KARYAWAN = penilai.ID_KARYAWAN
			INNER JOIN karyawan ON penilaian.KAR_ID_KARYAWAN = karyawan.ID_KARYAWAN
			INNER JOIN jabatan ON karyawan.ID_JABATAN = jabatan.ID_JABATAN
			WHERE
				penilaian.ID_PENILAIAN = "'.$id.'"
		');
	}

}
