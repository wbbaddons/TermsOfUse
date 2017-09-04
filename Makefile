WCF_FILES = $(shell find files_wcf -type f)
JS_MODULE_FILES = $(shell find files_wcf/js/Bastelstu.be -type f)

all: be.bastelstu.termsOfUse.tar

be.bastelstu.termsOfUse.tar: files_wcf.tar acptemplates.tar templates.tar *.xml LICENSE sql/*.sql language/*.xml
	tar cvf be.bastelstu.termsOfUse.tar --numeric-owner --exclude-vcs -- files_wcf.tar acptemplates.tar templates.tar *.xml LICENSE sql/*.sql language/*.xml

files_wcf.tar: $(WCF_FILES)
	tar cvf files_wcf.tar --numeric-owner --exclude-vcs --transform='s,files_wcf/,,' -- $^

acptemplates.tar: acptemplates/*.tpl
	tar cvf acptemplates.tar --numeric-owner --exclude-vcs --transform='s,acptemplates/,,' -- acptemplates/*.tpl

templates.tar: templates/*.tpl
	tar cvf templates.tar --numeric-owner --exclude-vcs --transform='s,templates/,,' -- templates/*.tpl

clean:
	-rm -f files_wcf.tar
	-rm -f templates.tar
	-rm -f acptemplates.tar

distclean: clean
	-rm -f be.bastelstu.termsOfUse.tar

.PHONY: distclean clean
