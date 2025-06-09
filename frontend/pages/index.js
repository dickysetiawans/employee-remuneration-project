import { useEffect, useState } from "react";
import axios from "axios";

const API_URL = "http://127.0.0.1:8000/api/task-records";

export default function Home() {
  const [tasks, setTasks] = useState([]);
  const [form, setForm] = useState({
    employee_name: "",
    task_description: "",
    date: "",
    hours_spent: "",
    hourly_rate: "",
    additional_charges: "",
  });
  const [editingId, setEditingId] = useState(null);

  // Fetch data
  const fetchTasks = async () => {
    try {
      const res = await axios.get(API_URL);
      setTasks(res.data);
    } catch (error) {
      alert("Gagal mengambil data");
    }
  };

  useEffect(() => {
    fetchTasks();
  }, []);

  // Handle form input change
  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  // Submit form (create or update)
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await axios.put(`${API_URL}/${editingId}`, form);
        setEditingId(null);
      } else {
        await axios.post(API_URL, form);
      }
      setForm({
        employee_name: "",
        task_description: "",
        date: "",
        hours_spent: "",
        hourly_rate: "",
        additional_charges: "",
      });
      fetchTasks();
    } catch (error) {
      alert("Gagal menyimpan data");
    }
  };

  // Edit task
  const handleEdit = (task) => {
    setForm({
      employee_name: task.employee_name,
      task_description: task.task_description,
      date: task.date,
      hours_spent: task.hours_spent,
      hourly_rate: task.hourly_rate,
      additional_charges: task.additional_charges || 0,
    });
    setEditingId(task.id);
  };

  // Delete task
  const handleDelete = async (id) => {
    if (!confirm("Yakin ingin menghapus?")) return;
    try {
      await axios.delete(`${API_URL}/${id}`);
      fetchTasks();
    } catch {
      alert("Gagal menghapus data");
    }
  };

  return (
    <div style={{ padding: 20, maxWidth: 800, margin: "auto" }}>
      <h1>Catatan Pekerjaan Pegawai</h1>

      <form onSubmit={handleSubmit} style={{ marginBottom: 20 }}>
        <input
          name="employee_name"
          placeholder="Nama Pegawai"
          value={form.employee_name}
          onChange={handleChange}
          required
        />
        <br />
        <textarea
          name="task_description"
          placeholder="Deskripsi Tugas"
          value={form.task_description}
          onChange={handleChange}
          required
        />
        <br />
        <input
          type="date"
          name="date"
          value={form.date}
          onChange={handleChange}
          required
        />
        <br />
        <input
          type="number"
          step="0.1"
          name="hours_spent"
          placeholder="Jumlah Jam Kerja"
          value={form.hours_spent}
          onChange={handleChange}
          required
        />
        <br />
        <input
          type="number"
          step="0.01"
          name="hourly_rate"
          placeholder="Tarif Per Jam"
          value={form.hourly_rate}
          onChange={handleChange}
          required
        />
        <br />
        <input
          type="number"
          step="0.01"
          name="additional_charges"
          placeholder="Biaya Tambahan"
          value={form.additional_charges}
          onChange={handleChange}
        />
        <br />
        <button type="submit">{editingId ? "Update" : "Tambah"}</button>
        {editingId && (
          <button
            type="button"
            onClick={() => {
              setEditingId(null);
              setForm({
                employee_name: "",
                task_description: "",
                date: "",
                hours_spent: "",
                hourly_rate: "",
                additional_charges: "",
              });
            }}
          >
            Batal
          </button>
        )}
      </form>

      <table border="1" cellPadding="5" style={{ width: "100%" }}>
        <thead>
          <tr>
            <th>Nama Pegawai</th>
            <th>Deskripsi Tugas</th>
            <th>Tanggal</th>
            <th>Jam Kerja</th>
            <th>Tarif/jam</th>
            <th>Biaya Tambahan</th>
            <th>Total Remunerasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {tasks.length === 0 && (
            <tr>
              <td colSpan="8" style={{ textAlign: "center" }}>
                Tidak ada data
              </td>
            </tr>
          )}
          {tasks.map((task) => (
            <tr key={task.id}>
              <td>{task.employee_name}</td>
              <td>{task.task_description}</td>
              <td>{task.date}</td>
              <td>{task.hours_spent}</td>
              <td>{task.hourly_rate}</td>
              <td>{task.additional_charges || 0}</td>
              <td>{task.total_remuneration || 0}</td>
              <td>
                <button onClick={() => handleEdit(task)}>Edit</button>{" "}
                <button onClick={() => handleDelete(task.id)}>Hapus</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}