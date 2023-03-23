package main

import log "github.com/sirupsen/logrus"

func main() {
	log.SetFormatter(&log.TextFormatter{
		ForceColors:            true,
		DisableLevelTruncation: true,
	})
	for i := 0; i <= 20; i++ {
		log.Info("Something noteworthy happened!")
		log.Warn("You should probably take a look at this.")
		log.Error("Something failed but I'm not quitting.")

	}
	log.Fatal("Bye.")
}
