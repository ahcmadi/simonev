<ul class="box">
    <li<%=$this->Page->showFormRealisasi==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.RealisasiPenerimaan')%>">Realisasi Penerimaan</a></li>
    <li<%=$this->Page->showFormA==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.formA')%>">Form A</a></li>            
    <li<%=$this->Page->showFormB==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.formB')%>">Form B</a></li>                
    <li<%=$this->Page->showPelaksanaanAnggaran==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.PelaksanaanAnggaran')%>">Pelaksanaan Anggaran</a></li>
    <li<%=$this->Page->showJenisPelaksanaan==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.JenisPelaksanaan')%>">Jenis Pelaksanaan</a></li>                
    <li<%=$this->Page->showJenisPembangunan==true?' id="submenu-active"':''%>><a href="<%=$this->Service->constructUrl('s.report.JenisPembangunan')%>">Jenis Pembangunan</a></li>                
    <li<%=$this->Page->showPejabat==true?' id="submenu-active"':''%>><a href="#">Pejabat</a>
        <ul>
            <li><a href="<%=$this->Service->constructUrl('s.report.PenggunaAnggaran')%>"<%=$this->Page->showPenggunaAnggaran==true?' style="color:#E01850"':''%>>Pengguna Anggaran</a></li>
            <li><a href="<%=$this->Service->constructUrl('s.report.KuasaPengguna')%>"<%=$this->Page->showKuasaPengguna==true?' style="color:#E01850"':''%>>Kuasa Pengguna Anggaran</a></li>
            <li><a href="<%=$this->Service->constructUrl('s.report.PPK')%>"<%=$this->Page->showPPK==true?' style="color:#E01850"':''%>>PPK</a></li>
            <li><a href="<%=$this->Service->constructUrl('s.report.PPTK')%>"<%=$this->Page->showPPTK==true?' style="color:#E01850"':''%>>PPTK</a></li>
        </ul>
    </li>
    <li<%=$this->Page->showLokasi==true?' id="submenu-active"':''%>><a href="#">Lokasi</a>
        <ul>
            <!--<li><a href="<%=$this->Service->constructUrl('s.report.Negara')%>"<%=$this->Page->showNegara==true?' style="color:#E01850"':''%>>Negara</a></li>-->
            <li><a href="<%=$this->Service->constructUrl('s.report.ReportDT1')%>"<%=$this->Page->showDT1==true?' style="color:#E01850"':''%>>Daerah Tingkat I</a></li>
            <li><a href="<%=$this->Service->constructUrl('s.report.ReportDT2')%>"<%=$this->Page->showDT2==true?' style="color:#E01850"':''%>>Daerah Tingkat II</a></li>
            <!--<li><a href="<%=$this->Service->constructUrl('s.report.Kecamatan')%>"<%=$this->Page->showKecamatan==true?' style="color:#E01850"':''%>>Kecamatan</a></li>-->
        </ul>
    </li>    
</ul>