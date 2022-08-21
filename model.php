<?php 
class db extends dbconn {

    public function __construct()
    {
        $this->initDBO();
    }
    
    // -- START -- SELECT
    public function cekMenusUser($username,$id_menus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                      tbl_users_menus.*,
                      tbl_menus.nama_menus,
                      tbl_menus.keterangan,
                      tbl_menus.status 
                    FROM
                      tbl_users_menus
                      INNER JOIN tbl_menus ON tbl_users_menus.id_menus = tbl_menus.id_menus
                    WHERE
                      tbl_users_menus.username = :username AND tbl_users_menus.id_menus = :id_menus AND tbl_menus.status = 'a' 
                    ";
            $stmt = $db->prepare($query);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$id_menus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensi($kode,$item)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND item = :item";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->bindParam("item",$item);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getReferensiByKode($kode)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_referensi WHERE kode = :kode AND status = 'a'";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getTable($kriteria)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_users WHERE username LIKE '%$kriteria%' OR hak_akses LIKE '%$kriteria%' OR kode_akses LIKE '%$kriteria%' ORDER BY username ASC LIMIT 100";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getDataById($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_users WHERE username = :id  LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getKodeUserById($kode)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT * FROM tbl_kode WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$kode);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getKodeUser($kode_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_kode.* 
                    FROM
                        tbl_kode_akses
                        INNER JOIN tbl_kode ON tbl_kode_akses.kode = tbl_kode.id 
                    WHERE
                        tbl_kode_akses.kode_akses = :kode_akses 
                    ORDER BY
                        tbl_kode.nama ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("kode_akses",$kode_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getMenuById($id,$idMenus)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_users_menus.*,
                        tbl_menu.nama_menu,
                        tbl_menu_sub.nama_menu_sub,
                        tbl_menus.nama_menus 
                    FROM
                        tbl_users_menus
                        INNER JOIN tbl_menus ON tbl_users_menus.id_menus = tbl_menus.id_menus
                        LEFT JOIN tbl_menu ON tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN tbl_menu_sub ON tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub 
                    WHERE
                        tbl_users_menus.username = :id AND tbl_users_menus.id_menus = :id_menus 
                    ORDER BY
                        LENGTH( tbl_menu.urut_menu ) ASC,
                        tbl_menu.urut_menu ASC,
                        LENGTH( tbl_menu_sub.urut_menu_sub ) ASC,
                        tbl_menu_sub.urut_menu_sub ASC,
                        LENGTH( tbl_menus.urut_menus ) ASC,
                        tbl_menus.urut_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("id_menus",$idMenus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getMenusAll()
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_menus.id_menus,
                        tbl_menu.nama_menu,
                        tbl_menu_sub.nama_menu_sub,
                        tbl_menus.nama_menus, 
                        tbl_menus.`status`
                    FROM
                        tbl_menus
                        LEFT JOIN tbl_menu ON tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN tbl_menu_sub ON tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub
                    ORDER BY
                        LENGTH( tbl_menu.urut_menu ) ASC,
                        tbl_menu.urut_menu ASC,
                        LENGTH( tbl_menu_sub.urut_menu_sub ) ASC,
                        tbl_menu_sub.urut_menu_sub ASC,
                        LENGTH( tbl_menus.urut_menus ) ASC,
                        tbl_menus.urut_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getMenusAllById($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_menus.id_menus,
                        tbl_menu.nama_menu,
                        tbl_menu_sub.nama_menu_sub,
                        tbl_menus.nama_menus, 
                        tbl_menus.`status`
                    FROM
                        tbl_menus
                        LEFT JOIN tbl_menu ON tbl_menus.id_menu = tbl_menu.id_menu
                        LEFT JOIN tbl_menu_sub ON tbl_menus.id_menu_sub = tbl_menu_sub.id_menu_sub
                        INNER JOIN tbl_users_menus ON tbl_menus.id_menus = tbl_users_menus.id_menus 
                    WHERE
                        tbl_users_menus.username = :id 
                    ORDER BY
                        LENGTH( tbl_menu.urut_menu ) ASC,
                        tbl_menu.urut_menu ASC,
                        LENGTH( tbl_menu_sub.urut_menu_sub ) ASC,
                        tbl_menu_sub.urut_menu_sub ASC,
                        LENGTH( tbl_menus.urut_menus ) ASC,
                        tbl_menus.urut_menus ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function getKodeAksesById($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                    tbl_kode.* 
                    FROM
                        tbl_users
                        INNER JOIN tbl_kode_akses ON tbl_users.kode_akses = tbl_kode_akses.kode_akses
                        INNER JOIN tbl_kode ON tbl_kode_akses.kode = tbl_kode.id 
                    WHERE
                        tbl_users.username = :id 
                    ORDER BY
                        tbl_kode.nama ASC";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }

    public function selectMenuHakAkses($hak_akses)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "SELECT
                        tbl_hak_akses.hak_akses,
                        tbl_hak_akses.id_menus,
                        tbl_hak_akses.c,
                        tbl_hak_akses.r,
                        tbl_hak_akses.u,
                        tbl_hak_akses.d 
                    FROM
                        tbl_hak_akses
                        INNER JOIN tbl_menus ON tbl_hak_akses.id_menus = tbl_menus.id_menus 
                    WHERE
                        tbl_hak_akses.hak_akses = :hak_akses";
            $stmt = $db->prepare($query);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stat[2] = $stmt->rowCount();
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            $stat[2] = [];
            return $stat;
        }
    }
    // -- END -- SELECT

    // -- START -- DELETE
    public function delete($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "DELETE FROM tbl_users WHERE username = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "HAPUS!";
            $stat[2] = "Data berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "HAPUS!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function deleteUserMenus($id)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "DELETE FROM tbl_users_menus WHERE username = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "HAPUS!";
            $stat[2] = "Data berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "HAPUS!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function hapus_menu($id)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "DELETE FROM tbl_users_menus WHERE id = :id";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil dihapus.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- DELETE

    // -- START -- CREATE
    public function create($username,$hak_akses,$kode_akses,$kode_user,$dashboard,$level)
    {
        $password = md5($username);
        $db = $this->dblocal;
        try
        {   
            $query = "INSERT INTO tbl_users (username, hak_akses, kode_akses, kode_user, dashboard, level, password, status) VALUES (:username, :hak_akses, :kode_akses, :kode_user, :dashboard, :level, :password, 'a')";
            $stmt = $db->prepare($query);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->bindParam("kode_akses",$kode_akses);
             $stmt->bindParam("kode_user",$kode_user);
            $stmt->bindParam("dashboard",$dashboard);
            $stmt->bindParam("level",$level);
            $stmt->bindParam("password",$password);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "TAMBAH!";
            $stat[2] = "Data berhasil ditambahkan.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "TAMBAH!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function tambah_menu($id,$username, $id_menus)
    {
        $db = $this->dblocal;
        try
        {   
            $query =    "INSERT INTO tbl_users_menus (id,username,id_menus,c,r,u,d) VALUES (:id,:username,:id_menus,'n','n','n','n')";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$id_menus);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil ditambahkan.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function createMenus($id,$username,$idMenus,$c,$r,$u,$d)
    {
        $db = $this->dblocal;
        try
        {   
            $query =    "INSERT INTO tbl_users_menus (id,username,id_menus,c,r,u,d) VALUES (:id,:username,:id_menus,:c,:r,:u,:d)";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("id_menus",$idMenus);
            $stmt->bindParam("c",$c);
            $stmt->bindParam("r",$r);
            $stmt->bindParam("u",$u);
            $stmt->bindParam("d",$d);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil ditambahkan.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- CREATE

    // -- START -- UPDATE
    public function update($username,$hak_akses,$kode_akses,$kode_user,$dashboard,$level,$status)
    {
        $db = $this->dblocal;
        try
        {   
            $query = "UPDATE tbl_users SET hak_akses = :hak_akses, kode_akses = :kode_akses, kode_user = :kode_user, dashboard = :dashboard, level = :level, status = :status WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam("username",$username);
            $stmt->bindParam("hak_akses",$hak_akses);
            $stmt->bindParam("kode_akses",$kode_akses);
            $stmt->bindParam("kode_user",$kode_user);
            $stmt->bindParam("dashboard",$dashboard);
            $stmt->bindParam("level",$level);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "EDIT!";
            $stat[2] = "Data berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "EDIT!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function reset_password($id)
    {
        $password = md5($id);
        $db = $this->dblocal;
        try
        {   
            $query = "UPDATE tbl_users SET password = :password WHERE username = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam("id",$id);
            $stmt->bindParam("password",$password);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "RESET PASSWORD!";
            $stat[2] = "Password berhasil dirubah menjadi '".$id."'";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = "RESET PASSWORD!";
            $stat[2] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_c_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_users_menus SET c = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_r_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_users_menus SET r = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_u_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_users_menus SET u = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }

    public function edit_d_menu($id, $status)
    {
        $db = $this->dblocal;
        try
        {
            $query =    "UPDATE tbl_users_menus SET d = :status WHERE id = :id ";
            $stmt = $db->prepare("$query");
            $stmt->bindParam("id",$id);
            $stmt->bindParam("status",$status);
            $stmt->execute();
            $stat[0] = true;
            $stat[1] = "Menu berhasil dirubah.";
            return $stat;
        }
        catch(PDOException $ex)
        {
            $stat[0] = false;
            $stat[1] = $ex->getMessage();
            return $stat;
        }
    }
    // -- END -- UPDATE

}