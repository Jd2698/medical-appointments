export const dateFormat = date => {
	const day = date.getDate().toString().padStart(2, '0')
	const month = (date.getMonth() + 1).toString().padStart(2, '0')
	const year = date.getFullYear()

	// 2024-07-22
	return `${year}-${month}-${day}`
}

export const appointmentDateFormat = date => {
	const day = date.getDate()
	const month = date.toLocaleString('es-ES', { month: 'long' })
	const year = date.getFullYear()

	// 24 de julio del 2024
	return `${day} de ${month} del ${year}`
}
