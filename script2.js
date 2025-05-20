document.getElementById('bookingForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const booking_date = document.getElementById('date').value;
    const time_slot = document.getElementById('time').value;
    const playground = document.getElementById('playground').value;

    const res = await fetch('/book', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, booking_date, time_slot, playground })
    });

    const data = await res.json();
    alert(data.message);
    loadBookings();
});

async function loadBookings() {
    const res = await fetch('/bookings');
    const bookings = await res.json();
    const container = document.getElementById('bookingsList');
    container.innerHTML = '';
    bookings.forEach(b => {
        container.innerHTML += `<p>${b.booking_date} | ${b.time_slot} | ${b.playground} - ${b.name}</p>`;
    });
}

loadBookings();


document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("bookingForm");
    const nameInput = document.getElementById("name");
    const dateInput = document.getElementById("date");
    const playgroundSelect = document.getElementById("playground");
    const bookingsList = document.getElementById("bookingsList");

    const messageDiv = document.createElement("div");
    messageDiv.id = "formMessage";
    form.parentNode.insertBefore(messageDiv, bookingsList);

    const bookings = [];

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Clear messages and list
        messageDiv.textContent = "";
        bookingsList.innerHTML = "";

        const name = nameInput.value.trim();
        const date = dateInput.value;
        const playground = playgroundSelect.value;

        // VALIDATION
        if (!name || name.length < 3) {
            showMessage("❌ Please enter a valid name (at least 3 characters).", "red");
            return;
        }

        if (!date) {
            showMessage("❌ Please select a valid date.", "red");
            return;
        }

        if (!playground) {
            showMessage("❌ Please select a playground.", "red");
            return;
        }

        // DUPLICATE CHECK
        const isDuplicate = bookings.some(booking =>
            booking.name === name &&
            booking.date === date &&
            booking.playground === playground
        );

        if (isDuplicate) {
            showMessage("⚠️ This booking already exists.", "orange");
            return;
        }

        // SAVE NEW BOOKING
        const newBooking = { name, date, playground };
        bookings.push(newBooking);

        showMessage("✅ Booking successful!", "lightgreen");

        const bookingItem = document.createElement("div");
        bookingItem.className = "bookingItem";
        bookingItem.textContent = `${name} - ${date} - ${playground}`;
        bookingsList.appendChild(bookingItem);

        form.reset();
    });

    function showMessage(message, color) {
        messageDiv.textContent = message;
        messageDiv.style.color = color;
        messageDiv.style.textAlign = "center";
        messageDiv.style.fontWeight = "bold";
        messageDiv.style.marginTop = "15px";
    }
});
