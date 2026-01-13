            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <!-- Menu Tabel-->
                            <!-- Core Menu Data Akses-->
                            <div class="sb-sidenav-menu-heading">Dashboard</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class='bxr bx-dashboard'></i> </div>
                                Data Akses
                            </a>

                            <!-- Core Menu Data Barang-->
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class='bxr bx-table'></i> </div>
                                Data Barang
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="stokPage.php">
                                        <div class="sb-nav-link-icon"><i class='bxr bx-database'></i></div>Stok Barang
                                    </a>
                                    <a class="nav-link" href="DataMasukPage.php">
                                        <div class="sb-nav-link-icon"><i class='bxr bx-database'></i></div>Barang Masuk
                                    </a>
                                    <a class="nav-link" href="#">
                                        <div class="sb-nav-link-icon"><i class='bxr bx-database'></i></div>Barang Keluar
                                    </a>
                                </nav>
                            </div>

                            <!-- Core Menu Input Barang-->
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Input Data
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <!-- Menu Input Barang-->
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <!-- Menu Input Barang Masuk-->
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        <i class='bxr  bx-arrow-in-right-square-half'></i>&nbsp Barang Masuk
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModalInLama"><i class='bxr bx-folder-down-arrow'></i>&nbsp Barang Lama</a>
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModalInBaru"><i class='bxr bx-folder-plus'></i>&nbsp Barang Baru</a>
                                        </nav>
                                    </div>

                                    <!-- Menu Input Barang Keluar-->
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        <i class='bxr  bx-arrow-out-left-square-half'></i>&nbsp Barang Keluar
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModalOut"><i class='bxr bx-folder-up-arrow'></i>&nbsp Input Barang</a>
                                    </div>
                                </nav>
                            </div>

                            <!-- Menu Keluar-->
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class='bxr  bx-cog'></i> </div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin <?php echo $_SESSION['username'] ?>
                    </div>
                </nav>
            </div>