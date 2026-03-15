hostname=$(shell cat /etc/hostname)

.PHONY: run

run:
	@bin/pkg-audit ~/.mycfg/package-lists/$(hostname).list
