require('dotenv').config();
const nodemailer = require('nodemailer');

exports.handler = async (event) => {
    if (event.httpMethod !== 'POST') {
        return {
            statusCode: 405,
            body: JSON.stringify({ message: 'Method Not Allowed' }),
        };
    }

    const { name, number, email, subject, message } = JSON.parse(event.body);

    if (!name || !number || !email || !subject || !message) {
        return {
            statusCode: 400,
            body: JSON.stringify({ status: 'error', message: 'Please fill all fields correctly.' }),
        };
    }

    try {
        let transporter = nodemailer.createTransport({
            service: 'gmail',
            auth: {
                user: process.env.EMAIL_USER,
                pass: process.env.EMAIL_PASS, 
            },
        });

        await transporter.sendMail({
            from: email,
            to: 'bradabee.lunga@gmail.com', 
            subject: `${subject}: ${name}`,
            html: `
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>Phone Number:</strong> ${number}</p>
                <p><strong>Email:</strong> ${email}</p>
                <p><strong>Subject:</strong> ${subject}</p>
                <p><strong>Message:</strong> ${message}</p>
            `,
        });

        return {
            statusCode: 200,
            body: JSON.stringify({ status: 'success', message: 'Message sent successfully!' }),
        };
    } catch (error) {
        return {
            statusCode: 500,
            body: JSON.stringify({ status: 'error', message: `Mailer Error: ${error.message}` }),
        };
    }
};
