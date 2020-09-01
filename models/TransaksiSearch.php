<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaksi;
use yii\data\SqlDataProvider;

/**
 * TransaksiSearch represents the model behind the search form about `app\models\Transaksi`.
 */
class TransaksiSearch extends Transaksi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idsubakun', 'idakundebet', 'idakunkredit', 'debet', 'kredit'], 'integer'],
            [['no_ref', 'keterangan', 'tanggal'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }else{
            $tanggaldari = date('Y-m-d');
            $tanggalsampai = date('Y-m-d');
        }
        
        $query = Transaksi::find()
            ->where(['>=', 'tanggal', $tanggaldari])
            ->andWhere(['<=', 'tanggal', $tanggalsampai]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idsubakun' => $this->idsubakun,
            'idakundebet' => $this->idakundebet,
            'idakunkredit' => $this->idakunkredit,
            'debet' => $this->debet,
            'kredit' => $this->kredit,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'no_ref', $this->no_ref])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        $query->orderBy(['tanggal' => SORT_ASC]);

        return $dataProvider;
    }

    public function searchJurnalUmum($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }

        $query = "
            select 
                tr.tanggal,
                sa.nama_sub_akun as nama_akun,
                tr.no_ref,
                tr.debet,
                tr.kredit,
                tr.keterangan
            from transaksi tr
            left join sub_akun sa on tr.idsubakun = sa.id
            where tanggal >= :tanggaldari
            and tanggal <= :tanggalsampai
            order by tanggal desc
        ";

        if(!isset($params['Transaksi']['tanggal_dari']) || !isset($params['Transaksi']['tanggal_sampai'])){
            $tanggaldari = '0000-00-00';
            $tanggalsampai = '0000-00-00';
        }
        //$query = "select * from transaksi";

        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'pagination' => false,
                'params' => [
                    ':tanggaldari'      => $tanggaldari,
                    ':tanggalsampai'    => $tanggalsampai
                ]
            ]);

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }

    public function searchBukuBesar($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }

        // $query = "
        //     select 
        //         @ak,
        //         case
        //             when @ak != tr.ke_akun then 
        //                 @s:=0
        //             end,
        //         date_format(tr.tanggal,'%d-%m-%Y') as tanggal,
        //         sa.nama_sub_akun,
        //         sk.nama_sub_akun as nama_akun,
        //         @d:=ifnull(debet,0) as ndebet, 
        //         @k:=ifnull(kredit,0) as nkredit,
        //         debet,
        //         kredit,
        //         tr.no_ref,
        //         @s:=@s+@d-@k as saldo,
        //         if(@s>=0, @s, null) as saldo_debet,
        //         abs(if(@s<0, @s, null)) as saldo_kredit,
        //         @ak:=tr.ke_akun 
        //     from transaksi tr
        //     inner join (select @s :=0, @ak := 0, id, nama_sub_akun from sub_akun) sa on tr.ke_akun = sa.id
        //     inner join sub_akun sk on tr.idsubakun = sk.id
        //     where tr.tanggal >= :tanggaldari and tr.tanggal <= :tanggalsampai
        //     order by tr.ke_akun 
        // ";

        $query = "
            select 
                *,
                if(saldo > 0, saldo, null) as saldo_debet,
                if(saldo < 0, abs(saldo), null) as saldo_kredit
            from v_bukubesar
            where tanggal >= :tanggaldari
            and tanggal <= :tanggalsampai
        ";
        if(!isset($params['Transaksi']['tanggal_dari']) || !isset($params['Transaksi']['tanggal_sampai'])){
            $tanggaldari = '0000-00-00';
            $tanggalsampai = '0000-00-00';
        }
        //$query = "select * from transaksi";

        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'pagination' => false,
                'params' => [
                    ':tanggaldari'      => $tanggaldari,
                    ':tanggalsampai'    => $tanggalsampai
                ]
            ]);

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }

    public function searchNeracaSaldo($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }

        $query = "
            select 
                distinct
                /*tr.tanggal AS tanggal,*/
                sa.nama_sub_akun AS nama_akun,
                if((
                    select 
                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                    from v_transaksi 
                    where idsubakun = tr.idsubakun 
                    and tanggal >= :tanggaldari
                    and tanggal <= :tanggalsampai
                ) > 0, (select 
                            sum((ifnull(debet,0) - ifnull(kredit,0))) 
                            from v_transaksi 
                            where idsubakun = tr.idsubakun 
                            and tanggal >= :tanggaldari
                            and tanggal <= :tanggalsampai), null) as debet,
            if((
                    select 
                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                    from v_transaksi 
                    where idsubakun = tr.idsubakun 
                    and tanggal >= :tanggaldari
                    and tanggal <= :tanggalsampai
                ) < 0, abs((select 
                            sum((ifnull(debet,0) - ifnull(kredit,0))) 
                            from v_transaksi 
                            where idsubakun = tr.idsubakun 
                            and tanggal >= :tanggaldari
                            and tanggal <= :tanggalsampai)), null) as kredit
                           
            from v_transaksi tr 
            inner join sub_akun sa on tr.idsubakun = sa.id
            where tanggal >= :tanggaldari
            and tanggal <= :tanggalsampai
            order by tr.idsubakun
        ";
        if(!isset($params['Transaksi']['tanggal_dari']) || !isset($params['Transaksi']['tanggal_sampai'])){
            $tanggaldari = '0000-00-00';
            $tanggalsampai = '0000-00-00';
        }
        //$query = "select * from transaksi";

        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'pagination' => false,
                'params' => [
                    ':tanggaldari'      => $tanggaldari,
                    ':tanggalsampai'    => $tanggalsampai
                ]
            ]);

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }

    public function searchLabaRugi($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }

        $query = "
            select 
                ak.nama_akun as kategori,
                labarugi.*,
                '' as total
            from (
            (
            select 
                            distinct
                                            sa.idakun,
                            sa.id,
                            sa.nama_sub_akun AS nama_akun,
                            if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) > 0, (select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai), null) as debet,
                        if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) < 0, abs((select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai)), null) as kredit
                                       
                        from transaksi tr 
                        inner join sub_akun sa on tr.ke_akun = sa.id
                        where tanggal >= :tanggaldari
                        and tanggal <= :tanggalsampai
                                    and sa.idakun = 4 
            )
            union all
            (
            select 
                            distinct
                                            sa.idakun,
                            sa.id,
                            sa.nama_sub_akun AS nama_akun,
                            if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) > 0, (select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai), null) as debet,
                        if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) < 0, abs((select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai)), null) as kredit
                                       
                        from transaksi tr 
                        inner join sub_akun sa on tr.ke_akun = sa.id
                        where tanggal >= :tanggaldari
                        and tanggal <= :tanggalsampai
                                    and sa.idakun = 5
            )
            ) labarugi 
            inner join akun ak on labarugi.idakun = ak.id


        ";
        if(!isset($params['Transaksi']['tanggal_dari']) || !isset($params['Transaksi']['tanggal_sampai'])){
            $tanggaldari = '0000-00-00';
            $tanggalsampai = '0000-00-00';
        }
        //$query = "select * from transaksi";

        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'pagination' => false,
                'params' => [
                    ':tanggaldari'      => $tanggaldari,
                    ':tanggalsampai'    => $tanggalsampai
                ]
            ]);

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }

    public function searchNeraca($params)
    {
        if(isset($params['Transaksi']['tanggal_dari']) || isset($params['Transaksi']['tanggal_sampai'])){

            $vtanggaldari   = explode('-', $params['Transaksi']['tanggal_dari']);
            $vtanggalsampai = explode('-', $params['Transaksi']['tanggal_sampai']);

            $tanggaldari    = $vtanggaldari[2]."-".$vtanggaldari[1]."-".$vtanggaldari[0];
            $tanggalsampai  = $vtanggalsampai[2]."-".$vtanggalsampai[1]."-".$vtanggalsampai[0];
        }

        $query = "
            select 
                -- ak.nama_akun as kategori,
                case 
                    when ak.nama_akun = 'Harta' then 
                        'Aktiva'
                    when ak.nama_akun = 'Hutang' then 
                        'Kewajiban'
                    when ak.nama_akun = 'Modal' then 
                        'Modal'
                end as kategori,
                neraca.*,
                '' as total
            from (
            (
            select 
                            distinct
                                            sa.idakun,
                            sa.id,
                            sa.nama_sub_akun AS nama_akun,
                            ka.nama_kategori,
                            if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) > 0, (select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai), null) as debet,
                        if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) < 0, abs((select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai)), null) as kredit
                                       
                        from transaksi tr 
                        inner join sub_akun sa on tr.ke_akun = sa.id
                        left join kategori_akun ka on sa.idkategoriakun = ka.id
                        where tanggal >= :tanggaldari
                        and tanggal <= :tanggalsampai
                                    and sa.idakun = 1 
            )
            union all
            (
            select 
                            distinct
                                            sa.idakun,
                            sa.id,
                            sa.nama_sub_akun AS nama_akun,
                            ka.nama_kategori,
                            if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) > 0, (select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai), null) as debet,
                        if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) < 0, abs((select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai)), null) as kredit
                                       
                        from transaksi tr 
                        inner join sub_akun sa on tr.ke_akun = sa.id
                        left join kategori_akun ka on sa.idkategoriakun = ka.id
                        where tanggal >= :tanggaldari
                        and tanggal <= :tanggalsampai
                                    and sa.idakun = 2
            )
                        union all 
                        (
            select 
                            distinct
                                            sa.idakun,
                            sa.id,
                            sa.nama_sub_akun AS nama_akun,
                            ka.nama_kategori,
                            if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) > 0, (select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai), null) as debet,
                        if((
                                select 
                                    sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                from transaksi 
                                where ke_akun = tr.ke_akun 
                                and tanggal >= :tanggaldari
                                and tanggal <= :tanggalsampai
                            ) < 0, abs((select 
                                        sum((ifnull(debet,0) - ifnull(kredit,0))) 
                                        from transaksi 
                                        where ke_akun = tr.ke_akun 
                                        and tanggal >= :tanggaldari
                                        and tanggal <= :tanggalsampai)), null) as kredit
                                       
                        from transaksi tr 
                        inner join sub_akun sa on tr.ke_akun = sa.id
                        left join kategori_akun ka on sa.idkategoriakun = ka.id
                        where tanggal >= :tanggaldari
                        and tanggal <= :tanggalsampai
                                    and sa.idakun = 3
            )
            ) neraca 
            inner join akun ak on neraca.idakun = ak.id


        ";
        if(!isset($params['Transaksi']['tanggal_dari']) || !isset($params['Transaksi']['tanggal_sampai'])){
            $tanggaldari = '0000-00-00';
            $tanggalsampai = '0000-00-00';
        }
        //$query = "select * from transaksi";

        $dataProvider = new SqlDataProvider([
                'sql' => $query,
                'pagination' => false,
                'params' => [
                    ':tanggaldari'      => $tanggaldari,
                    ':tanggalsampai'    => $tanggalsampai
                ]
            ]);

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }
}
