# Развёртывание проекта с помощью Docker и Makefile

## Введение

Это руководство описывает процесс развёртывания проекта на вашем локальном компьютере с использованием Docker и Docker Compose с помощью Makefile. Убедитесь, что у вас установлены Docker и Docker Compose перед началом.

## Шаги

1. **Клонирование проекта**

    * Склонируйте репозиторий проекта с помощью следующей команды:

        ```
        git clone <URL-репозитория> <название-проекта>
        cd <название-проекта>
        

    * Переименуйте файл `.env.example` в `.env`.

2. **Развёртывание контейнеров**

    * Запустите следующую команду, чтобы собрать и запустить контейнеры Docker:

        
        make up
 

        Это команда из Makefile автоматически создаст и запустит контейнеры для приложения.


6. **Завершение работы**

    * По завершении работы над проектом, остановите и удалите контейнеры Docker:


        make down


## Вывод

Теперь у вас должен быть развёрнутый проект Laravel, готовый к разработке и тестированию на вашем локальном компьютере.

## Дополнительные сведения

* Подробные сведения о Docker и Docker Compose можно найти в их официальных руководствах.
* Подробные сведения о Makefile можно найти в документации Makefile: https://www.gnu.org/software/make/manual/make.html.
* Удачи!
