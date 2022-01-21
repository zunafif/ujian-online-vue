<template>
  <div>
    <h1><center>Section Khusus : {{ listModul[0].nama_modul }}</center></h1>
    <el-divider />
    <el-table :data="listModul" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="No" prop="id" width="50">
        <template slot-scope="scope">
          <span>{{ scope.$index+1 }}</span>
        </template>
      </el-table-column>

      <el-table-column align="Left" label="Modul" width="120">
        <template slot-scope="scope">
          <span>{{ scope.row.nama_modul }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="Praktikum">
        <template slot-scope="scope">
          <span>{{ scope.row.nama }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Jumlah Bab" width="105">
        <template slot-scope="scope">
          <span>{{ scope.row.jumlah_bab }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="Materi" width="245">
        <template slot-scope="scope">
          <span>{{ scope.row.materi }}</span>
        </template>
      </el-table-column>
    </el-table>
    <el-divider />
    <el-button type="success" icon="el-icon-edit" @click="$emit('handleEditForm', listModul[0].id, listModul[0].nama_modul)">
      EDIT MODUL
    </el-button>
    <el-button type="danger" icon="el-icon-delete" @click="$emit('handleDelete', listModul[0].id, listModul[0].nama_modul)">
      HAPUS MODUL
    </el-button>
  </div>
</template>

<script>
export default {
  name: 'DetailModul',
  props: ['listModul'],
  data() {
    return {
      viewForm: false,
    };
  },
};
</script>

