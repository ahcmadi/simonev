<?php
prado::using ('Application.pages.m.dmaster.MainPageDMaster');
class PPK extends MainPageDMaster {	
	public function onLoad ($param) {		
		parent::onLoad ($param);
        $this->showPejabat=true;
        $this->showPPK=true;
        $this->createObjKegiatan();
		if (!$this->IsPostBack&&!$this->IsCallback) {			
            if (!isset($_SESSION['currentPagePPK'])||$_SESSION['currentPagePPK']['page_name']!='m.dmaster.PPK') {
                $_SESSION['currentPagePPK']=array('page_name'=>'m.dmaster.PPK','page_num'=>0);												
			}            
			$this->populateData ();
		}	
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePPK']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}
    protected function populateData () {         
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePPK']['page_num'];
		$jumlah_baris=$this->DB->getCountRowsOfTable ('ppk','nip_ppk');		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPagePPK']['page_num']=0;}
        $str="SELECT ppk.nip_ppk,ppk.nama_ppk,ppk.idunit,u.nama_unit FROM ppk LEFT JOIN unit u ON (u.idunit=ppk.idunit) ORDER BY ppk.nip_ppk ASC  LIMIT $offset,$limit";			        
		$this->DB->setFieldTable(array('nip_ppk','nama_ppk','idunit','nama_unit'));		
		$r=$this->DB->getRecord($str,$offset+1);        
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();
	}
    public function addProcess ($sender,$param) {
        $this->idProcess='add';
        $this->cmbAddBagian->DataSource=$this->kegiatan->getList('bagian',array('idbagian','kode_bagian','nama_bagian'),'kode_bagian',null,2);  
        $this->cmbAddBagian->dataBind();
	}
    public function changeStruktur ($sender,$param) {			
        switch ($sender->getId()) {
            case 'cmbAddBagian' :
                $this->idProcess='add';	
                $idbagian=$this->cmbAddBagian->Text;  
                $this->cmbAddUnitKerja->DataSource=array();
                $this->cmbAddUnitKerja->Enabled=false;
                $this->cmbAddUnitKerja->dataBind();
                if ($idbagian != 'none' && $idbagian != '') {
                    $result=$this->kegiatan->getList("unit WHERE idbagian=$idbagian",array('idunit','kode_unit','nama_unit'),'kode_unit',null,2);
                    if (count($result)> 1) {
						$this->cmbAddUnitKerja->DataSource=$this->kegiatan->getList("unit WHERE idbagian=$idbagian",array('idunit','kode_unit','nama_unit'),'kode_unit',null,2);  
                        $this->cmbAddUnitKerja->Enabled=true;
                        $this->cmbAddUnitKerja->dataBind();
					}
                }
            break;        
            case 'cmbEditBagian' :
                $this->idProcess='edit';
                $idbagian=$this->cmbEditBagian->Text;  
                $this->cmbEditUnitKerja->DataSource=array();
                $this->cmbEditUnitKerja->Enabled=false;
                $this->cmbEditUnitKerja->dataBind();
                if ($idbagian != 'none' && $idbagian != '') {
                    $result=$this->kegiatan->getList("unit WHERE idbagian=$idbagian",array('idunit','kode_unit','nama_unit'),'kode_unit',null,2);
                    if (count($result)> 1) {
						$this->cmbEditUnitKerja->DataSource=$this->kegiatan->getList("unit WHERE idbagian=$idbagian",array('idunit','kode_unit','nama_unit'),'kode_unit',null,2);  
                        $this->cmbEditUnitKerja->Enabled=true;
                        $this->cmbEditUnitKerja->dataBind();
					}
                }
            break;
        }
	}  
    public function cekNIP($sender,$param) {						
        $this->idProcess=$sender->getId()=='addNIP'?'add':'edit';
        $nip=$param->Value;		
        $idunit=$sender->getId()=='addNIP'?$this->cmbAddUnitKerja->Text:$this->cmbEditUnitKerja->Text;
        if ($nip != '') {
            try {   
                if ($this->hiddennip->Value!=$nip) {                    
                    if ($this->DB->checkRecordIsExist('nip_ppk','ppk',$nip," AND idunit=$idunit")) {                                
                        $nip=$this->nipFormat($nip);
                        throw new Exception ("<p class='msg error'>NIP ($nip) sudah tidak tersedia lagi pada unit kerja ini, silahkan ganti dengan yang lain.</p>");		
                    }                               
                }                
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }						
    }
	protected function saveData () {
		if ($this->Page->IsValid) {
            $idunit=$this->cmbAddUnitKerja->Text;
			$nip = addslashes(trim($this->txtAddNip->Text));
			$nama = addslashes(trim($this->txtAddNamaPejabat->Text));
			$str = 'INSERT INTO ppk (nip_ppk,idunit,nama_ppk) VALUES ';
			$str = $str . "('$nip',$idunit,'$nama')";
			$this->DB->insertRecord($str);
            $this->redirect('m.dmaster.PPK');			
		}
	}				
	public function editRecord ($sender,$param) {
		$this->idProcess='edit';
		$id=$this->getDataKeyField($sender,$this->RepeaterS); 
        $idunit=$sender->CommandParameter;
        $this->hiddenidunitkerja->Value=$idunit;
        $str = "SELECT idbagian,ppk.idunit,nip_ppk,nama_ppk FROM ppk LEFT JOIN unit u ON (u.idunit=ppk.idunit) WHERE nip_ppk='$id' AND ppk.idunit=$idunit";        
		$this->DB->setFieldTable(array('idbagian','idunit','nip_ppk','nama_ppk'));		
        $result=$this->DB->getRecord($str);                
		$this->hiddennip->Value=$result[1]['nip_ppk'];
        $idbagian=$result[1]['idbagian'];
        $this->cmbEditBagian->DataSource=$this->kegiatan->getList('bagian',array('idbagian','kode_bagian','nama_bagian'),'kode_bagian',null,2);  
        $this->cmbEditBagian->Text=$result[1]['idbagian'];	
        $this->cmbEditBagian->dataBind();	
        $this->cmbEditUnitKerja->DataSource=$this->kegiatan->getList("unit WHERE idbagian=$idbagian",array('idunit','kode_unit','nama_unit'),'kode_unit',null,2);  
        $this->cmbEditUnitKerja->Text=$result[1]['idunit'];
        $this->cmbEditUnitKerja->Enabled=true;
        $this->cmbEditUnitKerja->dataBind();
		$this->txtEditNip->Text=$result[1]['nip_ppk'];		
		$this->txtEditNamaPejabat->Text=$result[1]['nama_ppk'];
	}
	public function updateData($sender,$param) {
		if ($this->Page->IsValid) {
            $id=$this->hiddennip->Value;
			$nip = addslashes(trim($this->txtEditNip->Text));
			$nama = addslashes(trim($this->txtEditNamaPejabat->Text));
            $idunit=$this->cmbEditUnitKerja->Text;
            $idunit_old=$this->hiddenidunitkerja->Value;
			$str = "UPDATE ppk SET nip_ppk='$nip',idunit=$idunit,nama_ppk='$nama' WHERE nip_ppk='$id' AND idunit=$idunit_old";
			$this->DB->updateRecord($str);
            $this->redirect('m.dmaster.PPK');
		}
	}
    public function deleteRecord ($sender,$param) {		
        $id=$this->getDataKeyField($sender,$this->RepeaterS);
        $idunit=$sender->CommandParameter;
		$this->DB->deleteRecord("ppk WHERE nip_ppk='$id' AND idunit=$idunit");
		$this->populateData();
	}
}

?>