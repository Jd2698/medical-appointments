export const dateFormat = date => {
	const day = date.getDate().toString().padStart(2, '0')
	const month = (date.getMonth() + 1).toString().padStart(2, '0')
	const year = date.getFullYear()
	const hours = date.getHours().toString().padStart(2, '0')
	const minutes = date.getMinutes().toString().padStart(2, '0')

	// 2024-07-22 05:22
	return `${year}-${month}-${day} ${hours}:${minutes}`
}

export const appointmentDateFormat = date => {
	const day = date.getDate()
	const month = date.toLocaleString('es-ES', { month: 'long' })
	const year = date.getFullYear()
	const hour = date.getHours()
	const minute = date.getMinutes().toString().padStart(2, '0')

	// 24 de julio del 2024 5:00
	return `${day} de ${month} del ${year} ${hour}:${minute}`
}

export const addMinutes = (hour, add) => {
	// 05:00 -> 05:30
	const partesHora = hour.split(':')
	const fecha = new Date()

	fecha.setHours(parseInt(partesHora[0]))
	fecha.setMinutes(parseInt(partesHora[1]))

	fecha.setMinutes(fecha.getMinutes() + add)

	const newHour = fecha.getHours().toString().padStart(2, '0')
	const newMinutes = fecha.getMinutes().toString().padStart(2, '0')

	return `${newHour}:${newMinutes}`
}

export const rangeNotValid = (hora1, hora2) => {
	// 05:00 - 05:30 -> 05:25 x
	const formHour = parseHour(hora1)
	const firstRange = parseHour(hora2.start)
	const secondRange = parseHour(hora2.end)

	return formHour >= firstRange && formHour <= secondRange
}

function parseHour(hourString) {
	const [hour, minute] = hourString.split(':')
	let date = new Date()
	date.setHours(parseInt(hour))
	date.setMinutes(parseInt(minute))
	return date
}
