const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

app.use(cors());
app.use(bodyParser.json());
app.use(express.static('public'));

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', // your MySQL password
    database: 'playground_booking'
});

db.connect(err => {
    if (err) throw err;
    console.log("Connected to MySQL");
});

app.get('/bookings', (req, res) => {
    db.query('SELECT * FROM bookings', (err, results) => {
        if (err) return res.status(500).send(err);
        res.json(results);
    });
});

app.post('/book', (req, res) => {
    const { name, booking_date, time_slot, playground } = req.body;
    const sql = 'INSERT INTO bookings (name, booking_date, time_slot, playground) VALUES (?, ?, ?, ?)';

    db.query(sql, [name, booking_date, time_slot, playground], (err) => {
        if (err) {
            if (err.code === 'ER_DUP_ENTRY') {
                return res.status(400).json({ message: 'This slot is already booked.' });
            }
            return res.status(500).send(err);
        }
        res.json({ message: 'Booking successful!' });
    });
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
