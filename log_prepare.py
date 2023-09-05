# читает файл лог nginx и выводит топ 3 запроса по количеству за указанный промежуток времени
import re
import datetime
import os.path


def getTop(arr, limit):  # получаем топ элементов
    if len(arr) == 0:
        return arr
    sorted_arr = sorted(arr.items(), key=lambda x: -x[1])
    sorted_arr = dict(sorted_arr)
    return dict(list(sorted_arr.items())[:limit])


def inputTime():  # получение даты для диапазона проверки
    time_str = str(input("Enter first date in dd:mm:yyyy format: "))
    try:
        strptime = datetime.datetime.strptime(time_str, "%d:%m:%Y")
        time_one = int(strptime.timestamp())
        return time_one
    except ValueError as ex:
        print("Date input error!:")
        quit()


log_file = input('Enter log filename: ')

if not os.path.exists(log_file):
    print('file not exist!')
    quit()

RES_LIMIT = 3
stat = dict()   # словарь данных из лог файла
time_one = inputTime()  # первая дата диапазона
time_two = inputTime()  # вторая дата диапазона

if time_one > time_two:  # прверяем разность дат
    time_one, time_two = time_two, time_one

pattern = r"""- - \[(?P<dateandtime>\d{2}\/[a-z]{3}\/\d{4}:\d{2}:\d{2}:\d{2} (\+|\-)\d{4})\] ((\"(GET|POST) )(?P<url>.+)( HTTP\/1\.1"))"""
lineformat = re.compile(pattern, re.IGNORECASE)

logfile = open(log_file, "r")

for l in logfile.readlines():
    line_data = re.search(lineformat, l)

    if line_data:
        log_dict = line_data.groupdict()
        timestring = log_dict["dateandtime"]
        url = log_dict["url"]

        timestamp = int(datetime.datetime.strptime(
            timestring, "%d/%b/%Y:%H:%M:%S %z").timestamp())

        # проверяем диапазон даты
        if time_one <= timestamp <= time_two:
            if url in stat:
                stat[url] += 1
            else:
                stat[url] = 1

logfile.close()
res = getTop(stat, RES_LIMIT)

if len(res) > 0:
    print('\nTop urls:')
    for key in res:
        print('url: %s, count: %d' % (key, res[key]))
else:
    print('empty stats.')
